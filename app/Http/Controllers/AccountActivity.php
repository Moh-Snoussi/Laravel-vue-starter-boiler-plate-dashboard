<?php

namespace App\Http\Controllers;

use App\Transaction;

use Illuminate\Http\Request;
use function GuzzleHttp\json_decode;
use App\User;
use App\Account;


/**
 * A class responsible for processing and handling 
 * Methods:
 * Dashboard data:
 *    dashboard
 *    
 * Table handling:
 *    index.
 * 
 * Deposit and withdraw
 *    depositAndWithdraw
 * 
 * Transactions:
 *    transaction.
 * this class is called by 
 * file: route/api.php     route/web.php
 *
 * @author Mohamed Snoussi
 *
 */

class AccountActivity extends Controller
{
    // with draw limit
    private $limitWithDraw = -200;

    /**
     * dashboard
     * respond with the user data
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function dashboard(Request $request)
    {
        $user = $request->user(); // get the user from the request token
        return Transaction::where('cardNumber', $user->cardNumber)->orderBy('created_at', 'desc')->get(); //SQL query
    }


    /**
     * index
     * handel the table, pagination, sorting and searching,
     * this function get called from routes/api.php
     * 
     * 
     * @param  mixed $request
     *
     * @return void
     */
    public function index(Request $request)
    {
        $user = $request->user(); // get the user from the request token

        $perPage = $request['perPage']; // get the how many result per page
        $page = $request['page']; // get the requested page
        $search = json_decode($request['search'])->searchTerm; // get the search term if there is one
        $sort = json_decode($request['sort']); // this object holds the type(asc or desc ) and the row that need to be sorted row 

        //check if searching and sorting at the same time
        if (strlen($search) > 0 && strlen($sort->field) > 1) return (is_numeric(substr($search, 0, 3)) ? (Transaction::orderBy($sort->field, $sort->type)->where('cardNumber', $user->cardNumber)->where('secondPartyCardNumber', 'LIKE', '%' . $search . '%')->paginate($perPage)) //SQL query
            : (Transaction::orderBy($sort->field, $sort->type)->where('cardNumber', $user->cardNumber)->where('secondPartyName', 'LIKE', '%' . $search . '%')->paginate($perPage))); //SQL query

        // check if the user is searching 
        else if (strlen($search) > 0) return
            is_numeric(substr($search, 0, 2)) ? (Transaction::where('cardNumber', $user->cardNumber)::where('secondPartyCardNumber', 'LIKE', '%' . $search . '%')->orderBy('created_at', 'desc')->paginate($perPage))
            : (Transaction::where('cardNumber', $user->cardNumber)->where('secondPartyName', 'LIKE', '%' . $search . '%')->paginate($perPage));

        // check if sorting checked 
        else if (strlen($sort->field) > 1) return (Transaction::where('cardNumber', $user->cardNumber)->orderBy($sort->field, $sort->type)->orderBy('created_at', 'desc')->paginate($perPage));
        //if nothing of the above condition return a normal pagination
        else return Transaction::where('cardNumber', $user->cardNumber)->orderBy('created_at', 'desc')->paginate($perPage);
    }



    /**
     * depositAndWithdraw
     * this method check the url and proceed
     * we retrieve the refer url from the header where we see if it comes from deposit or withdraw 
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function depositAndWithdraw(Request $request)
    {
        if ($request->user()) {

            //getting the data from the request 
            $user = $request->user();
            $newAmount = $request['amount'];
            $reference = $request['reference'];
            $operation = $request->header()['referer'][0];

            // Check the url and assign the variable accordingly

            if (strpos($operation, 'deposit') !== false) $operation = 'deposit';
            elseif (strpos($operation, 'withdraw') !== false) $operation = 'withdraw';
            // if nothing of the above condition then send an error
            else return response()->json([
                'errors' => [
                    'message' => 'Unknown url'
                ]
            ], 350);
        }

        //adding or subtract depend on the operation
        $operation == 'withdraw' ? $newAmountx = -(int)$newAmount : $newAmountx = (int)$newAmount;


        // checking if the user is valid
        if (isset($user)) { // get the last user row where we find his balance
            $latestRecords = Transaction::where('cardNumber', $user->cardNumber)->orderBy('created_at', 'desc')->first();
            // if the user have records in his account add or subtract otherwise create a new balance records
           
            $updatedBalance = $latestRecords ? (int)$latestRecords['currentBalance'] + $newAmountx : $newAmountx;
            if($operation == 'withdraw' && (int)$updatedBalance < $this->limitWithDraw ){
                return response()->json([
                    'success' => false, 'errors' => ['header' => 'There was an error', 'body' => 'Your balance reached the withdraw limit! withdraw limit : '. $this->limitWithDraw . '$. Your current balance : ' . (int)$latestRecords['currentBalance']  .'$.' ]
                ], 270); 

            }
        } else { // user unauthorized
            return response()->json([
                'errors' => [
                    'message' => 'Unauthorized'
                ]
            ], 401);
        }

        // creating the Transaction object

        $Transaction = new Transaction([
            'name' => $user->name,
            'cardNumber' => $user->cardNumber,
            'userId' => $user['id'],
            'currentBalance' => $updatedBalance,
            'amount' => $newAmount,
            'reference' => $reference,
            'operation' => $operation
        ]);

        // saving in the data base
        $Transaction->save();

        //success response

        return response()->json([
            'success' => true, 'messages' => ['header' => 'Operation ' . $operation . ' succeeded', 'body' => 'You have a total of ' . $updatedBalance . '$ at your account']
        ], 201); // response 
    }

    /**
     * transaction
     * this method get the card number, the amount and create tow new row in the data base 
     * one for the sender were we subtract the amount sent from his balance and save in a new row with new balance and the effected operation
     * the other is for the receiver we add the amount sent to his balance and save in a new row with new balance and the effected operation
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function transaction(Request $request)
    {
        if ($request->user()) { // if user authenticated 
            // take the data from the request
            $user = $request->user();
            $sentAmount = $request['amount'];
            $reference = $request['reference'];
            $operation = $request->header()['referer'][0];
            $secondPartyCardNumber = $request['secondPartyCardNumber'];
            $secondPartyName = $request['secondPartyName'];


            if (strpos($operation, 'transaction') !== false) { // is this a valid transaction
                // get the latest records of the sender and the receiver from database
                $latestSenderRecords = Transaction::where('cardNumber', $user->cardNumber)->orderBy('created_at', 'desc')->first();
                $latestReceiverRecords = Transaction::where('cardNumber', $secondPartyCardNumber)->orderBy('created_at', 'desc')->first();
                // adding to the receiver balance
                $updatedReceiverBalance = $latestReceiverRecords ? (int)$latestReceiverRecords->currentBalance + (int)$sentAmount : (int)$sentAmount;
                // subtracting from the sender ballance
                $updatedSenderBalance = $latestSenderRecords ? (int)$latestSenderRecords->currentBalance - (int)$sentAmount : -(int)$sentAmount;
            } else { // this is not valid transaction
                return response()->json([
                    'errors' => [
                        'message' => 'Unauthorized'
                    ]
                ], 401);
            }

            // checking if the sender have sufficient balance 
            if ($latestSenderRecords->currentBalance < $sentAmount) return response()->json([
                'success' => false, 'errors' => ['header' => 'There was an error', 'body' => 'Your balance is insufficient for such operation !']
            ], 270); // response 



            // All fine transition will be proceeded

            // if the receiver have no records we get his data from the user table
            if (!$latestReceiverRecords)
            $latestReceiverRecords = User::where('cardNumber', $secondPartyCardNumber)->first();



            // Receiver new record Transaction
            $ReceiverNewColumn = new Transaction([
                'cardNumber' => $secondPartyCardNumber,
                'name' => $latestReceiverRecords->name,
                'userId' => $latestReceiverRecords->id,
                'currentBalance' => $updatedReceiverBalance,
                'amount' => $sentAmount,
                'reference' => $reference,
                'operation' => 'TransactionFrom',
                'secondPartyCardNumber' => $latestSenderRecords['cardNumber'],
                'secondPartyName' => $latestSenderRecords->name,

            ]);

            // sender new record Transaction

            $SenderNewColumn = new Transaction([
                'cardNumber' => $user['cardNumber'],
                'userId' => $user['id'],
                'name' => $latestSenderRecords->name,

                'currentBalance' => $updatedSenderBalance,
                'amount' => $sentAmount,
                'reference' => $reference,
                'operation' => 'TransactionTo',
                'secondPartyCardNumber' => $latestReceiverRecords['cardNumber'],
                'secondPartyName' => $latestReceiverRecords['name'],
            ]);

            return ($SenderNewColumn->save() && $ReceiverNewColumn->save()) ? // check if Transaction objects are inserted in the database

                response()->json([ // 
                    'success' => true, 'messages' => ['header' => 'Transaction succeeded', 'body' => 'You sent ' . $sentAmount . ' $ to ' . $latestReceiverRecords['name'] . ' successfully, you have now a total of ' . $updatedSenderBalance . '$ at your account']
                ], 201) // success
                : // or  response()->json([
                    response()->json(['success' => false, 'errors' => ['header' => 'There was an error', 'body' => 'Transaction canceled']
                ], 350); // error 
        }
    }

    /**
     * cardChecker
     * check if the provider card number is valid
     * and the sender has enough to send
     * this method executed before the transaction
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function cardChecker(Request $request)
    {
        //getting the request data
        $sender = $request->user();
        $receiver = $request['receiver'];
        $amount = $request['amount'];
        $checkReceiver = User::where('cardNumber', $receiver)->first(); // checking if the card number exist
        $latestSenderRecords = Transaction::where('userId', $sender->id)->orderBy('created_at', 'desc')->first();

        if ((int)$latestSenderRecords->currentBalance < (int)$amount ) return response()->json([ // the provided card number is same as the sender ard number
            'success' => false, 'errors' => ['header' => 'There was an error', 'body' => 'Your balance is insufficient for such operation!']
        ], 270); // response 

        if ($sender->cardNumber == $receiver) return response()->json([ // the provided card number is same as the sender ard number
            'success' => false, 'errors' => ['header' => 'There was an error', 'body' => 'The account you addressed is yours !']
        ], 270); // response 

        return $checkReceiver ? // found a card number
            response()->json([ // sending the name and details anout the transaction
                'success' => true, 'messages' => ['header' => 'Please confirm', 'body' => 'You are about to send ' . $request['amount'] . ' $ to ' . $checkReceiver->name . '.']
            ], 201)
            : // else  error response()->json([
                response()->json(['success' => false, 'errors' => ['header' => 'There was an error', 'body' => 'The receiver credit card is not registered']
            ], 270); // response 
    }
}


<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Config;

class MailChimpController extends Controller
{


    public $mailchimp;
    public $listId = 'b2a0a0c93f';


    public function __construct(\Mailchimp $mailchimp)
    {
        $this->mailchimp = $mailchimp;
    }


    public function manageMailChimp()
    {
        return view('mailchimp');
    }


    public function subscribe(Request $request)
    {
    	$this->validate($request, [
	    	'email' => 'required|email',
        ]);
        try {
            $this->mailchimp
            ->lists
            ->subscribe(
                $this->listId,
                ['email' => $request->input('email')]
            );
            return redirect()->back()->with('success','Email Subscribed successfully');
        } catch (\Mailchimp_Error $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }


    public function sendCompaign(Request $request)
    {
    	$this->validate($request, [
	    	'subject' => 'required',
	    	'to_email' => 'required',
	    	'from_email' => 'required',
	    	'message' => 'required',
        ]);


        try {

	        $options = [
	        'list_id'   => $this->listId,
	        'subject' => $request->input('subject'),
	        'from_name' => 'kathiravan',
	        'from_email' => 'rubanshanthi24@gmail.com',
	        'to_name' => $request->input('to_email')
	        ];
	        $content = [
	        'html' => $request->input('message'),
	        'text' => strip_tags($request->input('message'))
            ];

	        $campaign = $this->mailchimp->campaigns->create('regular', $options, $content);
	        $this->mailchimp->campaigns->send($campaign['id']);

        	return redirect()->back()->with('success','send campaign successfully');
        } 
        catch (\Exception $e) {

        	return redirect()->back()->with('error',$e->getMessage());

        }
    }


}

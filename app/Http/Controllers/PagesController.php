<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use App\Models\Customer;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Mail;

class PagesController extends Controller
{

    public function __construct()
    {

    }

    //
    public function home(Request $request)
    {
        $data = [];

        $customer = $this->decode_token($request->token);

        if(!empty($request->token)){
            if(!empty($customer)){
                $data['customer'] = Customer::find($customer->id);
                $data['email'] = Customer::find($customer->id)['email'];
                $data['upload_img'] = '';
                $sum = 0;
                $documents_count = 0;
                if(!empty($request->type)){
                    $document_type = explode(",", $request->type) ?? array($request->type);
                    foreach($document_type as $key => $row){
                       if($row != 'id' && $row != 'ssn' && $row != 'bill'){
                            $document_type = array("ssn", "bill", "id");
                       }
                    }
                }else{
                    $username = env('DISPUTE_USER');
                    $password = env('DISPUTE_PASSWORD');
                    $uriAPI = env('DISPUTE_API_URL') . "/Kpo8b97f31fbe65wrl/documents_by_contact";
                    $data_api = array(
                        "email" => $data['email'],
                        "field" => "email"
                    );
                    $documents = $this->sendC786APIRequest($uriAPI, $username, $password, $data_api, "GET");

                    $document_type = array("ssn", "bill", "id");
                    $document_type_contact = array();
                    if(!empty($documents['row']['documents'])){
                        $sum = count($documents['row']['documents']);
                        foreach ($documents['row']['documents'] as $key_doc => $value) {
                            $document_type_contact[$key_doc] = $value['document_type'];
                            $data['document'][$documents_count] = $this->document_configuration_exist(count($documents['row']['documents']), $value['document_type'], $value);
                            $documents_count++;
                        }
                        $document_type = array_values(array_diff($document_type, $document_type_contact) ?? array("ssn", "bill", "id"));
                    }
                }
                $data['document_type'] = json_encode($document_type);
                if(!empty($document_type)){
                    foreach($document_type as $key => $row){
                        $data['document'][$documents_count] = $this->document_configuration($key, count($document_type) + $sum, $row);
                        $documents_count++;
                    }
                }else{
                    $data['upload_img'] = 'd-none button_img';
                }

                return view('index', $data);
            }else{
                return redirect()->route('login')->with('error', 'Your verification token has expired. Please re-generate a new token in order to continue updating the form data.');
            }
        }else{
            return redirect()->route('login')->with('error', 'Your verification token has expired. Please re-generate a new token in order to continue updating the form data.');
        }
    }

    protected function document_configuration($key, $cant, $document_type){
        $data = array();
        if($cant == 1){
            $col = "col-md-12 one-document";
        }elseif($cant == 2){
            $col = "col-md-6 one-document";
        }else{
            $col = "col-md-4";
        }
        $key = $key + 1;
        if($document_type == "id"){
            $data['col-md'] = $col;
            $data['src'] = "/images/driver-license.jpg";
            $data['id_img'] = "imagen_id";
            $data['order_document'] = "order_document_id";
            $data['document_id'] = "document-id";
            $data['span'] = "<span>".$key."</span>";
            $data['title'] = "Number Id";
        }
        if($document_type == "ssn"){
            $data['col-md'] = $col;
            $data['src'] = "/images/social.jpg";
            $data['id_img'] = "imagen_social";
            $data['order_document'] = "order_document_ssn";
            $data['document_id'] = "document-ssn";
            $data['span'] = "<span>".$key."</span>";
            $data['title'] = "Social Security";
        }
        if($document_type == "bill"){
            $data['col-md'] = $col;
            $data['src'] = "/images/bill.jpg";
            $data['id_img'] = "imagen_bill";
            $data['order_document'] = "order_document_bill";
            $data['document_id'] = "document-bill";
            $data['span'] = "<span>".$key."</span>";
            $data['title'] = "Bill Payment";
        }

        return $data;
    }

    protected function document_configuration_exist($cant, $document_type, $documents){
        $data = array();
        $url_image = $documents['filename'];
        if($document_type == "id"){
            $data['col-md'] = "col-md-4";
            $data['src'] = env('DISPUTE_API_URL')."/assets/images/docs/".$url_image;
            $data['id_img'] = "imagen_id";
            $data['order_document'] = "order_document_check";
            $data['document_id'] = "document-id";
            $data['span'] = "<span><i class='mdi mdi-check-all'></i></span>";
            $data['title'] = "Number Id";
        }
        if($document_type == "ssn"){
            $data['col-md'] = "col-md-4";
            $data['src'] = env('DISPUTE_API_URL')."/assets/images/docs/".$url_image;
            $data['id_img'] = "imagen_social";
            $data['order_document'] = "order_document_check";
            $data['document_id'] = "document-ssn";
            $data['span'] = "<span><i class='mdi mdi-check-all'></i></span>";
            $data['title'] = "Social Security";
        }
        if($document_type == "bill"){
            $data['col-md'] = "col-md-4";
            $data['src'] = env('DISPUTE_API_URL')."/assets/images/docs/".$url_image;
            $data['id_img'] = "imagen_bill";
            $data['order_document'] = "order_document_check";
            $data['document_id'] = "document-bill";
            $data['span'] = "<span><i class='mdi mdi-check-all'></i></span>";
            $data['title'] = "Bill Payment";
        }

        return $data;
    }

    protected function decode_token($token)
    {
        $key = env('JWT_SECRET');
        try {
            $data = JWT::decode($token, new Key($key, 'HS256'));
            $decoded_array = (array) $data;
            return $decoded_array['data'];
        } catch (\Throwable $th) {
            return;
        }
    }

    public function uppy(Request $request)
    {
       return view('uppy');
    }

    public function login(Request $request)
    {
        return view('login');
    }

    public function create_token(Request $request)
    {
        $this->validate($request,[
            "email_token" => "required"
        ]);

        $customer = Customer::where('email', $request->email_token)->first();
        if ($customer){
            $token = $this->getJWTToken($customer);
            $data['customer'] = $customer;
            $data['token'] = $token;
            Mail::to($request->email_token)->send(new SendEmail($data));
            return response()->json(['error' => false]);
        }else{
            return response()->json(['error' => true, 'message' => 'Unable to sign user authentication successfully'], 400);
        }
    }

    public function upload_image(Request $request){
        $response = null;
        $document_type= $request->type ?? "";
        $contact = $this->contact_curl($request->email, $document_type);
        if(!empty($contact->status) && $contact->status == TRUE){
            if ($request->hasFile('file')) {
                $original_filename = $request->file('file')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = '/CP/';
                $image = 'CP-' . time() . '.' . $file_ext;
                $type = "default";

                if(isset($request['file'])){
                    \Storage::disk('public')->put($destination_path.$image,  \File::get($request['file']));
                }

                if ($request->file('file')->move('\storage'.$destination_path, $image)) {
                    $response = $this->native_curl('/storage'.$destination_path . $image, $contact->row->id, $type, $original_filename, $document_type);
                    @unlink($destination_path . $image);

                    if(isset($response->status) && $response->status == 'success')
                    return $this->responseRequestSuccess($image);
                    else
                    return $this->responseRequestError('Error subiendo imagen');

                } else {
                return $this->responseRequestError('Cannot upload file');
                }
            } else {
                return $this->responseRequestError('File not found');
            }
        }
        else{
            if(!empty($contact) && $contact->status == false){
                return $this->responseRequestError($contact->message);
            }else{
                return $this->responseRequestError('Contact not found');
            }
        }
    }

    protected function responseRequestSuccess($ret){
        return response()->json(['status' => 'success', 'data' => $ret], 200)
                      ->header('Access-Control-Allow-Origin', '*')
                      ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    protected function responseRequestError($message = 'Bad request', $statusCode = 400){
        return response()->json(['status' => 'error', 'error' => $message], $statusCode)
                      ->header('Access-Control-Allow-Origin', '*')
                      ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    function sendC786APIRequest( $serviceURL, $user, $pass, $data, $method = "post" ){
        $serviceName = "external";
        $urlParts = parse_url( $serviceURL );
        if ( $urlParts !== FALSE && ! empty( $urlParts["host"] ) ) {
            $hostParts = explode( ".", $urlParts["host"] );
            $serviceName = ucfirst( $hostParts[0] );
        }
        if ( ! ( $headers = @get_headers( $serviceURL, 1 ) ) ) {
            return array( "status" => FALSE, "message" => "Impossible connect with " . $serviceName . " API" );
        }
        if ( ! array_key_exists("WWW-Authenticate", $headers ) ) {
            return array( "status" => FALSE, "message" => "Impossible get " . $serviceName . " API Digest header" );
        }
        $matches = array();
        preg_match_all('@(realm|nonce|qop)=[\'"]?([^\'",]+)@', $headers["WWW-Authenticate"], $matches);
        if ( empty( $matches[1] ) || empty( $matches[2] ) ) {
            return array( "status" => FALSE, "message" => "Impossible get " . $serviceName . " API Digest header keys" );
        }
        $digest = array_combine( $matches[1], $matches[2] );
        if ( ! array_key_exists("realm", $digest) || ! array_key_exists("nonce", $digest ) || ! array_key_exists("qop", $digest ) ) {
            return array( "status" => FALSE, "message" => "Impossible get " . $serviceName . " API Digest header keys" );
        }
        $method = strtoupper( $method );
        if ( $method !== "POST" && $method !== "GET" ) $method = "POST";
        $uri = "/";
        $nc = uniqid();
        $cnonce = uniqid();
        $streamContextArray = array(
            "http" => array(
                "method" => $method,
                "header" => "Content-Type: " . ( $method !== "GET" ? "application/x-www-form-urlencoded" : "text/html" ) . "\r\n" .
                    "Authorization: Digest " .
                    "username=\"" . $user . "\", " .
                    "nonce=\"" . $digest["nonce"] . "\", " .
                    "qop=\"" . $digest["qop"] . "\", " .
                    "uri=\"" . $uri . "\", " .
                    "nc=\"" . $nc . "\", " .
                    "cnonce=\"" . $cnonce . "\", " .
                    "response=\"" . md5( md5( $user . ":" . $digest["realm"] . ":" . $pass ) . ":" . $digest["nonce"] . ":" . $nc . ":" . $cnonce . ":" . $digest["qop"] . ":" . md5( $method . ":" . $uri ) ) . "\""
            )
        );
        if ( $method !== "GET" ) $streamContextArray["http"]["content"] = http_build_query( $data );
        $response = @file_get_contents(
            $serviceURL . ( $method !== "GET" ? "" : "?" . http_build_query( $data ) ),
            FALSE,
            stream_context_create( $streamContextArray )
        );
        if ( empty( $response ) ) {
            return array( "status" => FALSE, "message" => "Impossible connect with " . $serviceName . " API method" );
        }
        if ( ! ( $response = json_decode( $response, TRUE ) ) ) {
            return array( "status" => FALSE, "message" => "Wrong " . $serviceName . " API method response" );
        }
        return $response;
    }

    protected function contact_curl($email, $type){
        $username = env('DISPUTE_USER');
        $password = env('DISPUTE_PASSWORD');
        $uriAPI = env('DISPUTE_API_URL') . "/Kpo8b97f31fbe65wrl/contact_by_field?email=".$email."&field=email&type=".$type."";

        $curl_handle = curl_init();

        curl_setopt($curl_handle, CURLOPT_URL, $uriAPI);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($curl_handle, CURLOPT_USERPWD, $username . ':' . $password);

        $buffer = curl_exec($curl_handle);

        curl_close($curl_handle);
        $result = json_decode($buffer);

        if(!empty($result)){
            return  $result;
        }
    }

    protected function native_curl($file_name_with_full_path, $contactId, $type, $name, $document_type){
        $username = env('DISPUTE_USER');
        $password = env('DISPUTE_PASSWORD');
        $uriAPI = env('DISPUTE_API_URL') . "/Kpo8b97f31fbe65wrl/upload_image";

        $curl_handle = curl_init();

        $cFile = curl_file_create($file_name_with_full_path);
        $post = array('contactID' => $contactId, 'type' => $type, 'name' => $name, 'file'=> $cFile, 'document_type'=>$document_type);

        curl_setopt($curl_handle, CURLOPT_URL, $uriAPI);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_POST, 1);
        curl_setopt($curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl_handle, CURLOPT_USERPWD, $username . ':' . $password);

        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);

        $result = json_decode($buffer);

        if(isset($result->status) && $result->status == 'success')
          return  $result;
        else
          return NULL ;
    }

    protected function getJWTToken($value)
    {
        $time = time();
        $payload = [
            'iat' => $time,
            'nbf' => $time,
            'exp' => $time+3600*24,
            'data' => [
                'id' => $value['id'],
                'email' => $value['email']
            ]
        ];
        $key =  env('JWT_SECRET');
        $alg = 'HS256';
        $token = JWT::encode($payload,$key,$alg);
        return $token;
    }
}

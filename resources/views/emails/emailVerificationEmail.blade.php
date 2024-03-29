@extends('emails.layout')
@section('message')
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td style="padding: 15px; text-align: center;">
		  <table width="600" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;  border: solid 1px #e0e0e0;" align="center" class="main-table">			  
          <tbody>
            <tr>
              <td align="center" style="background-color: #f9f9f9; border-bottom: solid 1px #e0e0e0; padding:30px 15px;">
					<a href="{{url('/')}}" > 
						<img src="{{asset('images/logo.png')}}" alt="{{ env('APP_NAME', '') }}"> </a>
				</td>
            </tr>
            <tr>
              <td style="font-family: 'arial';background-color: #fff; color: #000;  text-align: left; padding: 15px; font-size: 14px; line-height: 22px;">
				Dear <strong>{{ucwords($register_as)}}</strong>,<br><br>
				  Your are successfully registered on {{ env('APP_NAME', '') }}. Please click on below link for verify your email.
				</td>
            </tr>
			
            <tr>
              <td style="text-align: center; padding: 15px;">
				 	<a href="{{ route('user.verify', $token) }}" style="display: inline-block; margin: 0 auto; background-color: #3696fc; color: #fff; font-weight: bold;font-family: 'arial'; text-decoration: none; padding: 15px 30px; font-size: 18px; letter-spacing: 1px; text-transform: uppercase;">Verify Email</a>
				 </td>
            </tr>
			 <tr>
              <td>&nbsp;</td>
            </tr>

             <tr>
              <td style="padding: 15px;">
                   if you have any problem to open above link, then please copy below url on your browser.
		<br/>
    {{ route('user.verify', $token) }}
                </td>
            </tr> 
			 <tr>
              <td style="background-color: #f9f9f9; border-bottom: solid 1px #e0e0e0; padding:30px 15px;">
				 
				 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tbody>
					
					 
					<tr>
					  <td align="center" style=" padding: 2px;">
				  <table width="" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                       
                      <td style="font-family: 'arial';">{{ env('APP_NAME', '') }} (c) {{date('Y')}} All rights reserved </td>
                    </tr>
                  </tbody>
                </table>
				</td>
					</tr>
					
					
				  </tbody>
				</table>

				 
				 </td>
            </tr> 
          </tbody>
        </table>
		</td>
    </tr>
  </tbody>
@endsection
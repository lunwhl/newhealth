
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
<title>{{ config('app.name') }}</title>

<style type="text/css">

    body{width: 100%; background-image:url("http://newleaf.com.my/img/slides/slide.jpg"); background-repeat: no-repeat; background-size: cover; background-position: center center; background-color: #383434; margin:0; padding:0; -webkit-font-smoothing: antialiased;mso-margin-top-alt:0px; mso-margin-bottom-alt:0px; mso-padding-alt: 0px 0px 0px 0px;}

    p,h1,h2,h3,h4{margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;}

    span.preheader{display: none; font-size: 1px;}

    html{width: 100%;}

    table{font-size: 12px;border: 0;}

    .menu-space{padding-right:25px;}

    a,a:hover { text-decoration:none; color:#FFF;}


    @media only screen and (max-width:640px)
    {
        body {width:auto!important;}
        table [class=main] {width:440px !important;}
        table [class=two-left] {width:420px !important; margin:0px auto;}
        table [class=full] {width:100% !important; margin:0px auto;}
        table [class=two-left-inner] {width:400px !important; margin:0px auto;}
        table [class=menu-icon] { display:none;}

    }

    @media only screen and (max-width:479px)
    {
        body {width:auto!important;}
        table [class=main]  {width:310px !important;}
        table [class=two-left] {width:300px !important; margin:0px auto;}
        table [class=full] {width:100% !important; margin:0px auto;}
        table [class=two-left-inner] {width:280px !important; margin:0px auto;}
        table [class=menu-icon] { display:none;}  
    }

</style>

</head>

<body yahoo="fix" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<!--Main Table Start-->

    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" valign="middle">
        
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" valign="middle">
                            <table width="450" border="0" align="center" cellpadding="0" cellspacing="0" class="main">
                                <tr>
                                    <td height="90" align="center" valign="top" style="font-size:90px; line-height:90px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                
                <!-- Logo -->
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" valign="middle">
                            <table width="450" border="0" align="center" cellpadding="0" cellspacing="0" class="main">
                                <tr>
                                    <td align="center" valign="top" bgcolor="#FFFFFF" style="-moz-border-radius: 25px 25px 0px 0px; border-radius: 25px 25px 0px 0px; border-bottom:#e2e3e3 solid 1px;">
                                        <table width="380" border="0" align="center" cellpadding="0" cellspacing="0" class="two-left-inner">
                                            <tr>
                                                <td height="35" align="center" valign="top" style="font-size:35px; line-height:35px;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td align="center" valign="top">
                                                    <table border="0" align="center" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td align="center" valign="middle"><a href="https://portal.newleaf.com.my"><img  src="https://portal.newleaf.com.my/img/logo.png" width="135" alt="" /></a></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="20" align="center" valign="top" style="font-size:20px; line-height:20px;">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                
                <!-- Actual mail content -->
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" valign="middle">
                            <table width="450" border="0" align="center" cellpadding="0" cellspacing="0" class="main">
                                <tr>
                                    <td align="center" valign="top" bgcolor="#FFFFFF">
                                        <table width="380" border="0" align="center" cellpadding="0" cellspacing="0" class="two-left-inner">
                                            <tr>
                                                <td height="60" align="center" valign="top" style="font-size:60px; line-height:60px;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td align="center" valign="top">
                                                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td align="center" valign="top">
                                                                <table width="70" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <td align="center" valign="top"><img  src="{{ auth()->user()->avatar_path }}" width="70" height="70" alt="" style="-moz-border-radius: 70px; border-radius: 70px;" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center" valign="top">&nbsp;</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                  
                                                        <tr>
                                                            <td align="center" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#4c4c4c; font-weight:normal; line-height:36px;">{{ __('mail.hello') }}! {{ auth()->user()->name }}</td>
                                                        </tr>
                                  
                                                        <tr>
                                                            <td align="center" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:30px; color:#4c4c4c; font-weight:normal;">{{ __('mail.welcome') }}</td>
                                                        </tr>
                                  
                                                        <tr>
                                                            <td height="10" align="center" valign="top" style="font-size:10px; line-height:10px;">&nbsp;</td>
                                                        </tr>

                                                        <tr>
                                                            <td align="center" valign="top" style="font-family:'Open Sans', sans-serif, Verdana; font-size:15px; color:#4c4c4c; font-weight:normal; line-height:24px; padding:0px 35px;">{{ __('mail.thank-you-register') }}</td>
                                                        </tr>
                                  
                                                        <tr>
                                                            <td align="center" valign="top">&nbsp;</td>
                                                        </tr>
                                  
                                                        <tr>
                                                            <td align="center" valign="top">
                                                                <table width="155" border="0" align="center" cellpadding="0" cellspacing="0">
                                                                    <tr>
                                                                        <td height="50" align="center" valign="middle" bgcolor="#fe573c" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#FFF; font-weight:normal; text-transform:uppercase; -moz-border-radius: 30px; border-radius: 30px;"><div style="text-decoration:none; color:#FFF;">{{ auth()->user()->referral_code }}</div></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td align="center" valign="top">&nbsp;</td>
                                                        </tr>

                                                        <tr>
                                                            <td align="center" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#ccc; font-weight:normal; line-height:28px;">Newleaf Plantation Berhad</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#ccc; font-weight:bold; line-height:28px;">Copyright &copy; 2018 newleaf.com.my </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="50" align="center" valign="top" style="font-size:50px; line-height:50px;">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" valign="middle">
                            <table width="450" border="0" align="center" cellpadding="0" cellspacing="0" class="main">
                                <tr>
                                    <td align="center" valign="top" bgcolor="#FFFFFF" style="-moz-border-radius:0px 0px 25px 25px; border-radius:0px 0px 25px 25px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                
                <!-- Copyright note -->
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" valign="middle">
                            <table width="450" border="0" align="center" cellpadding="0" cellspacing="0" class="main">
                                <tr>
                                    <td align="center" valign="top">
                                        <table width="380" border="0" align="center" cellpadding="0" cellspacing="0" class="two-left-inner">
                                            <tr>
                                                <td height="35" align="center" valign="top" style="font-size:35px; line-height:35px;">&nbsp;</td>
                                            </tr>
                                            
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" valign="middle">
                            <table width="450" border="0" align="center" cellpadding="0" cellspacing="0" class="main">
                                <tr>
                                    <td height="90" align="center" valign="top" style="font-size:90px; line-height:90px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

<!--Main Table End-->

</body>
</html>


<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
        {{ config('get.SYSTEM_APPLICATION_NAME') }}
        </title>
</head>
<body><div>
<table align="center" cellpadding="0" cellspacing="0"  style="border:1px solid #dddddd;" width="650">
  <tbody>
    <tr>
      <td>
      <table cellpadding="0" cellspacing="0" style="background:#3c8dbc; border-bottom:1px solid #dddddd; padding:15px;" width="100%">
        <tbody>
          <tr>
            <td><a href="{{ \App::make('url')->to('/') }}" target="_blank"><img style="max-width: 100px;max-height: 100px;" width="100px" alt="" border="0" src="{!!  asset('storage/settings/' . config('get.MAIN_LOGO')) !!}" alt="{{ config('get.SYSTEM_APPLICATION_NAME') }}" /></a></td>
          </tr>
        </tbody>
      </table>
      </td>
    </tr>
    <tr>
      <td style="background:#ffffff; padding:15px;">
      <table cellpadding="0" cellspacing="0" width="100%">
        <tbody>
          <tr>
            <td style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; color:#000000; font-size:16px;">
             {!!$body!!}
            </td>
          </tr>
          <tr>
            <td style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; color:#043f8d; font-size:16px; vertical-align:middle; text-align:left; padding-top:20px;">

            </td>
          </tr>
        </tbody>
      </table>
      </td>
    </tr>
    <tr>
      <td style="background:#3c8dbc; border-top:1px solid #dddddd; text-align:center; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; color:#ffffff; padding:12px; font-size:12px; text-transform:uppercase; font-weight:normal;">{{ "Copyright &copy; " . date("Y"). " ". config("get.SYSTEM_APPLICATION_NAME")  }}</td>
    </tr>
  </tbody>
</table>
</div>
</body>
</head>
</html>
<!DOCTYPE html>
<html>

<head>
  <title>Ticket</title>
  <style>
    @import url(//fonts.googleapis.com/css?family=Poppins:300,400,500);

    * {
      font-family: 'Poppins', sans-serif;
      background-color: #fff;
    }

    body {
      background-color: #fff;
    }

    .container {
      max-width: 80%;
      margin: 0 auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }

    td {
      padding: 10px;
      font-weight: 400;
      font-size: 18px;
      color: #333;
      width: 33%;
    }

    img {
      max-width: 100%;
      height: auto;
      display: block;
      margin: 0 auto;
    }

    .logo {
      display: inline-block;
      vertical-align: top;
      margin-bottom: 2rem;
      margin-right: 20px;
      width: 100px;
    }

    .caption {
      display: inline-block;
      color: rgb(70, 148, 70);
      margin-bottom: 40px;
      text-align: center;
      /* added property */
    }

    /* make footer class stick in bottom */
    .footer {
      margin-top: 110mm;
    }
  </style>
</head>

<body>
  <div class="container">
    <header style="margin-bottom:20mm;">
      <table width="100%" style="margin-bottom:0.1mm;vertical-align: bottom;  font-size: 9pt; color: #000088;">
        <tr>
          <td width="33%" align="left"><img src="{{ $club_logo }}" width="100px" /></td>
          <td width="33%" align="center"></td>
          <td width="33%" align="right"><img src="{{ URL::asset('assets/images/ettan.png') }}" width="70px" /></td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td width="50%" align="center">
              <h2 class="caption">@lang('app.season_ticket') - {{ $season_year }}</h2>
          </td>
        </tr>
      </table>
    </header>

    <table>

      <tr>
        <td>@lang('app.name')</td>
        <td>{{ $name }}</td>
        <td rowspan="7"><img src="{{ $qr_image }}" alt="Image"></td>
      </tr>
      <tr>
        <td>@lang('app.ticket_number')</td>
        <td>{{ $ticket_number }}</td>
      </tr>
      <tr>
        <td>@lang('app.serial_number')</td>
        <td>{{ $serial_number }}</td>
      </tr>
      <tr>
        <td>@lang('app.season_year')</td>
        <td>{{ $season_year }}</td>
      </tr>
      <tr>
        <td>@lang('app.age_group')</td>
        <td>{{ $age_group }}</td>
      </tr>
      <tr>
        <td>@lang('app.privilege')</td>
        <td>{{ $category }}</td>
      </tr>
      <tr>
        <td>@lang('app.section')</td>
        <td>{{ $section }}</td>
      </tr>
      <tr>
        <td>@lang('app.ticket_price')</td>
        <td>{{ $price }}</td>
      </tr>
    </table>

    <div class="footer">
      <img src="{{ URL::asset('assets/images/sponsors-logos.png') }}" alt="">
    </div>
  </div>
</body>

</html>

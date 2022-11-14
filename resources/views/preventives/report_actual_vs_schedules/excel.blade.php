<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report Project Preventive</title>
</head>
<body>
    <table id="table" cellspacing="0" width="100%" cellpadding="10" border="1" style="border: 1px solid black;">
        <thead>
          <tr>
              <th rowspan="2">Location</th>
              <th rowspan="2">Tenant</th>
              <th rowspan="2">Asset Name</th>
              <th colspan="{{ $data['total_days'][0] }}" id="thMonth1"><center>{{ $data['descs'][0] }}</center></th>
              <th colspan="{{ $data['total_days'][1] }}" id="thMonth2"><center>{{ $data['descs'][1] }}</center></th>
              <!-- <th class="text-center">#</th>  --->
          </tr>
          <tr>
            @for ($i = 0; $i < count($data['total_days']); $i++)
                @for($j = 1; $j <= $data['total_days'][$i]; $j++)
                    <th>{{ (string) $j }}</th>
                @endfor
            @endfor
          </tr>
        </thead>
        <tbody>
            {!! $data['html'] !!}
        </tbody>
    </table>
</body>
</html>
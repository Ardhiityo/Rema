<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('assets/logo/favicon.png') }}" type="image/x-icon">
    <title>Author Reports</title>
</head>

<body>
    <table style="margin: 0 auto">
        <thead>
            <tr>
                <td style="padding-right: 10px; display: flex; align-items: center;">
                    <img src="{{ asset('assets/logo/favicon.png') }}" alt="unival" width="100px">
                </td>
                <td style="text-align: center">
                    <p style="font-weight: bold; font-size: 20px;">Kementerian Pendidikan Tinggi, Sains dan
                        Teknologi
                        <br>
                        Universitas Al-Khairiyah <br>
                        Fakultas Ilmu Komputer
                    </p>
                    <p style="font-weight: normal; font-size: 15px;">
                        Alamat : Jl.K.H.Enggus Arja No.1 Citangkil Kota Cilegon, Banten 42441 <br>
                        No. telepon: (0254) 7877057 Website: unival-cilegon.ac.id</h1>
                    </p>
                </td>
            </tr>
        </thead>
    </table>

    <div style="border-bottom: 2px double black;"></div>

    <br>

    <main style="font-size: 15px">
        <h4 style="text-align: center">
            Repositories In {{ $year }} <br>
            {{ $sub_title }}
        </h4>
        <br>

        <table style="text-align: center; border-collapse: collapse; margin: 0 auto;">
            <thead>
                <tr>
                    <th
                        style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; width: 35px">
                        No
                    </th>
                    <th
                        style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; width: 130px;">
                        Author
                    </th>
                    <th
                        style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; width: 180px;">
                        Title
                    </th>
                    <th
                        style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; width: 100px;">
                        Categories
                    </th>
                    <th
                        style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; width: 110px;">
                        Study Program
                    </th>
                    <th
                        style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; width: 70px;">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($meta_data as $data)
                    <tr>
                        <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                            {{ $loop->iteration }}
                        </td>
                        <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                            {{ $data->author_name }} ({{ $data->author_nim }})
                        </td>
                        <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                            {{ $data->title }}
                        </td>
                        <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                            {{ $data->categories }}
                        </td>
                        <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                            {{ $data->study_program }}
                        </td>
                        <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                            {{ $data->status }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>
        <p style="text-align: right;">
            Cilegon, {{ Carbon\Carbon::parse(now())->locale('id')->isoFormat('DD MMMM YYYY') }}
        </p>
        <br>

        <table>
            <tr>
                <td style="text-align: center">
                    <p>{{ $coordinator_data->position }} <br>
                        Universitas Al-Khairiyah
                    </p>
                </td>
            </tr>
            <tr>
                <td style="text-align: center">
                    <br>
                    <br>
                    <br>
                    <br>
                    <p style="border-bottom: 1px solid black;">
                        <b>{{ $coordinator_data->name }}</b>
                    </p>
                    <p style="font-weight: semi-bold"><b>NIDN : {{ $coordinator_data->nidn }}</b></p>
                </td>
            </tr>
        </table>

    </main>
</body>

</html>

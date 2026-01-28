<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('assets/logo/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Activity Reports</title>
</head>

<body>
    <table class="text-center d-flex justify-content-center">
        <thead>
            <tr>
                <td>
                    <img src="{{ asset('assets/logo/favicon.png') }}" alt="unival" width="100px">
                </td>
                <td style="font-size: 15px">
                    <h5 class="h-5">
                        <strong>
                            Kementerian Pendidikan Tinggi, Sains dan Teknologi
                            <br>
                            Universitas Al-Khairiyah <br>
                            Fakultas Ilmu Komputer
                        </strong>
                    </h5>
                    <p>Alamat : Jl.K.H.Enggus Arja No.1 Citangkil Kota
                        Cilegon, Banten 42441 <br> No. telepon: (0254) 7877057 Website: unival-cilegon.ac.id </p>
                </td>
            </tr>
        </thead>
    </table>

    <div style="border-bottom: 2px double black;"></div>

    <br>
    <br>
    <h6 class="h-6 text-center">
        Repository Activities In {{ $year }}
    </h6>
    <br>
    <br>

    <main class="text-center d-flex justify-content-center">
        <table>
            <thead>
                <tr>
                    <th
                        style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; width: 35px">
                        No
                    </th>
                    <th
                        style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; width: 200px;">
                        Title
                    </th>
                    <th
                        style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; width: 170px;">
                        Author
                    </th>
                    <th
                        style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; width: 110px;">
                        Study Program
                    </th>
                    <th
                        style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; width: 110px;">
                        Views
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
                            {{ $data->title }}
                        </td>
                        <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                            {{ $data->author }} ({{ $data->nim }})
                        </td>
                        <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                            {{ $data->study_program }}
                        </td>
                        <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                            @foreach ($data->activities->items as $item)
                                <span>
                                    {{ $item->category }} {{ $item->total }}</span>
                                <br>
                            @endforeach
                            Total : {{ $data->total_views }}
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>

    <br>
    <br>

    <h6 class="h-6 text-end">
        Cilegon, {{ Carbon\Carbon::parse(now())->locale('id')->isoFormat('DD MMMM YYYY') }}
    </h6>

</body>

</html>

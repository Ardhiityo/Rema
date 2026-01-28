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
        Repositories In {{ $year }} <br>
        {{ $sub_title }}
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
                @foreach ($authors as $author)
                    <tr>
                        <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                            {{ $loop->iteration }}
                        </td>
                        <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                            {{ $author->name }} ({{ $author->nim }})
                        </td>
                        <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                            {{ $author->meta_data }}
                        </td>
                        <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                            {{ $author->categories }}
                        </td>
                        <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                            {{ $author->study_program }}
                        </td>
                        <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                            {{ $author->status }}
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

    <br>
    <br>

    <section class="d-flex justify-content-end">
        <table>
            <tr>
                <td class="text-center">
                    <h6 class="h-6 text-center">{{ $coordinator_data->position }}</h6>
                    <br>
                    <br>
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <h6 class="h-6 text-center" style="border-bottom: 1px solid black;">
                        {{ $coordinator_data->name }}
                    </h6>
                    <h6 class="h-6 text-center" style="margin-top: -7px">NIDN. {{ $coordinator_data->nidn }}</h6>
                </td>
            </tr>
        </table>
    </section>
</body>

</html>

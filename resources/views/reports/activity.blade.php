<table>
    <thead>
        <tr>
            <th style=" text-align: center; vertical-align: middle;">
                <img src="{{ public_path('assets/logo/favicon.png') }}" width="100" height="100">
            </th>
            <th style="text-align: center; vertical-align: middle; font-size: 12px; font-weight: 500; " colspan="11">
                <h1>Kementrian Pendidikan Tinggi, Sains, dan Teknologi</h1>
                <h1>Universitas Al-Khairiyah</h1>
                <h1>Fakultas Ilmu Komputer</h1>
                <p>Alamat : Jl.K.H. Enggus Arja No. 1 Citangkil Kota Cilegon, Banten 42441</p>
                <p>No. Telpon: (0254) 7877057 Website: unival-cilegon.ac.id</p>
            </th>
        </tr>
        <tr>
            <th style="border-top: 2px double black;" colspan="12"></th>
        </tr>
    </thead>
</table>

<table>
    <tr></tr>
    <tr>
        <td colspan="12" style="text-align: center; font-weight: bold; font-size: 12px;">
            Repository Activities In {{ $year }}
        </td>
    </tr>
    <tr></tr>
</table>

<table>
    <thead>
        <tr>
            <th style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;"
                colspan="1">No</th>
            <th style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;"
                colspan="5">Title</th>
            <th style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;"
                colspan="2">Author</th>
            <th style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;"
                colspan="2">
                Study Program
            </th>
            <th style="font-weight: bold; border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px"
                colspan="2">Views</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($meta_data as $data)
            <tr>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px"
                    colspan="1">
                    {{ $loop->iteration }}
                </td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px"
                    colspan="5">
                    {{ $data->title }}
                </td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px"
                    colspan="2">
                    {{ $data->author }} ({{ $data->nim }})
                </td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px"
                    colspan="2">
                    {{ $data->study_program }}
                </td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px"
                    colspan="2">
                    @foreach ($data->activities->items as $item)
                        <span>
                            {{ $item->category }} {{ $item->total }}</span>
                        <br>
                    @endforeach
                    Total : {{ $data->total_views }}
                </td>
            </tr>
        @endforeach
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
    </tbody>
</table>

<table>
    <tr>
        <td colspan="5"></td>
        <td colspan="7" style="font-weight: bold; text-align: right; font-size: 10px;">
            <h3>Cilegon, {{ Carbon\Carbon::parse(now())->locale('id')->isoFormat('DD MMMM YYYY') }}</h3>
        </td>
    </tr>
</table>

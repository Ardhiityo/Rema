<table>
    <tr>
        <th rowspan="4">
            <img src="{{ public_path('assets/logo/favicon.png') }}" width="100" height="100">
        </th>
        <th style="font-size: 14px; font-weight: bold; text-align: left;" colspan="10">
            UNIVERSITAS AL-KHAIRIYAH
        </th>
    </tr>
    <tr>
        <td>
            Website
        </td>
        <td colspan="14">
            : www.unival-cilegon.ac.id
        </td>
        <td style="font-size: 14px; font-weight: bold; text-align: right;" colspan="6">
            FAKULTAS ILMU KOMPUTER
        </td>
    </tr>
    <tr>
        <td>
            e-Mail
        </td>
        <td colspan="14">
            : humas@unival-cilegon.ac.id
        </td>
        <td style="font-size: 12px; font-weight: bold; text-align: right;" colspan="6">TEKNIK INFORMATIKA</td>
    </tr>
    <tr>
        <td>
            Alamat
        </td>
        <td colspan="8">
            : Jalan Kh. Ahmad Dahlan Kel. Citangkil Kota Cilegon
        </td>
        <td style="font-size: 12px; font-weight: bold; text-align: right;" colspan="12">MANAJEMEN INFORMATIKA</td>
    </tr>
</table>

<table>
    <tr>
        <td colspan="22" style="border-top: 2px double black;"></td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr>
        <td colspan="22" style="text-align: center; font-weight: bold; font-size: 14px;">
            REPOSITORY ACTIVITIES IN {{ $year }}
        </td>
    </tr>
    <tr></tr>
    <tr></tr>
</table>

<table>
    <thead>
        <tr>
            <th style="font-weight: bold; border: 1px solid black; text-align: center;">NO</th>
            <th style="font-weight: bold; border: 1px solid black; text-align: center;" colspan="8">TITLE</th>
            <th style="font-weight: bold; border: 1px solid black; text-align: center;" colspan="5">AUTHOR</th>
            <th style="font-weight: bold; border: 1px solid black; text-align: center;" colspan="2">NIM</th>
            <th style="font-weight: bold; border: 1px solid black; text-align: center;" colspan="3">
                STUDY PROGRAM
            </th>
            <th style="font-weight: bold; border: 1px solid black; text-align: center;" colspan="3">VIEWS</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($meta_data as $data)
            <tr>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle;">
                    {{ $loop->iteration }}
                </td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle;" colspan="8">
                    {{ $data->title }}
                </td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle;" colspan="5">
                    {{ $data->author }}
                </td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle;" colspan="2">
                    {{ $data->nim }}
                </td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle;" colspan="3">
                    {{ $data->study_program }}
                </td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle;" colspan="3">
                    @foreach ($data->activities->items as $item)
                        <span>{{ $item->category }} {{ $item->total }}</span>,
                    @endforeach
                    <br>
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
        <td colspan="1"></td>
        <td colspan="4"></td>
        <td colspan="10"></td>
        <td colspan="7" style="font-weight: bold; text-align: center;">
            <h3>Cilegon, {{ Carbon\Carbon::parse(now())->locale('id')->isoFormat('DD MMMM YYYY') }}</h3>
        </td>
    </tr>
</table>

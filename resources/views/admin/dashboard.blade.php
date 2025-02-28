<x-app-layout>
    <h1>Selamat Datang, {{ auth()->user()->name }}</h1>
    <p>Anda log masuk sebagai <strong>Admin Negeri</strong> untuk negeri {{ auth()->user()->negeri }}.</p>

    <h2>Senarai Pegawai dalam Negeri Anda</h2>
    <table>
        <tr>
            <th>Nama</th>
            <th>Emel</th>
            <th>Tindakan</th>
        </tr>
        @foreach ($pegawai as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <button>Edit</button>
                <button>Padam</button>
            </td>
        </tr>
        @endforeach
    </table>
</x-app-layout>

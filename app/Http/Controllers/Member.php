<?php

namespace App\Http\Controllers;

use App\ComplaintSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\PriceList;
use App\Transaction;
use App\TransactionDetail;
use App\User;
use App\UserVoucher;
use App\Voucher;

class Member extends Controller
{
    protected $logged_email;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->logged_email = session()->get('login');
            return $next($request);
        });
    }

    /**
     * Fungsi untuk menampilkan halaman dashboard member (Beranda)
     */
    public function index()
    {
        $user = User::where('email', '=', $this->logged_email)->first();
        $transaksi_terakhir = Transaction::where('member_id', $user->id)->get();

        return view('member.index', compact('user', 'transaksi_terakhir'));
    }

    /**
     * Fungsi untuk menampilkan daftar harga
     */
    public function harga()
    {
        $user = User::where('email', '=', $this->logged_email)->first();
        $satuan = PriceList::where('category_id', 1)->get();
        $kiloan = PriceList::where('category_id', 2)->get();

        return view('member.harga', compact('user', 'satuan', 'kiloan'));
    }

    /**
     * Fungsi untuk menampilkan halaman riwayat transaksi member
     */
    public function riwayatTransaksi()
    {
        $user = User::where('email', '=', $this->logged_email)->first();
        $transaksi = Transaction::where('member_id', $user->id)->get();
        return view('member.riwayat', compact('user', 'transaksi'));
    }

    /**
     * Fungsi untuk menampilkan halaman poin
     */
    public function poin()
    {
        $user = User::where('email', '=', $this->logged_email)->first();
        $vouchers = Voucher::where('active_status', 1)->get();
        $memberVouchers = UserVoucher::where([
            'user_id' => $user->id,
            'used' => 0
        ])->get();
        return view('member.poin', compact('user', 'vouchers', 'memberVouchers'));
    }

    /**
     * Fungsi untuk menukar poin
     */
    public function tukarPoin($id_voucher)
    {
        $user = User::where('email', '=', $this->logged_email)->first();

        // Ambil data poin yang diperlukan untuk menukar voucher
        $voucher = Voucher::where('id', $id_voucher)->first();

        // Cek apakah poin member mencukupi untuk menukar voucher
        // Jika poin mencukupi, tambahkan ke tabel users voucher
        if ($user->point >= $voucher->point_need) {
            $user_voucher = new UserVoucher([
                'voucher_id' => $id_voucher,
                'user_id' => $user->id,
                'used' => 0
            ]);
            $user_voucher->save();

            // Update point member
            $user->point = $user->point - $voucher->point_need;
            $user->save();

            //Redirect ke poin dan pesan sukses
            return redirect('member/poin')->with('success', 'Poin berhasil ditukar menjadi voucher!');
        } else {
            return redirect('member/poin')->with('error', 'Poin tidak mencukupi untuk menukar voucher!');
        }
    }

    /**
     * Fungsi untuk menampilkan halaman saran komplain
     */
    public function saranKomplain()
    {
        $user = User::where('email', '=', $this->logged_email)->first();
        $saran_komplain = ComplaintSuggestion::where('user_id', $user->id)->get();
        return view('member.saran', compact('user', 'saran_komplain'));
    }

    /**
     * Fungsi untuk mengirimkan saran komplain
     */
    public function kirimSaranKomplain(Request $request)
    {
        $request->validate([
            'isi_sarankomplain' => 'required'
        ]);

        $user = User::where('email', '=', $this->logged_email)->first();

        $complaint_suggestion = new ComplaintSuggestion([
            'body' => $request->input('isi_sarankomplain'),
            'type' => $request->input('tipe'),
            'user_id' => $user->id,
            'reply' => ''
        ]);

        $complaint_suggestion->save();

        return redirect('member/saran')->with('success', 'Saran/komplain berhasil dikirim!');
    }

    /**
     * Fungsi untuk menampilkan halaman detail transaksi
     */
    public function detailTransaksi($id_transaksi)
    {
        $user = User::where('email', '=', $this->logged_email)->first();
        $transaksi = TransactionDetail::where('transaction_id', $id_transaksi)->get();
        return view('member.detail', compact('user', 'transaksi', 'id_transaksi'));
    }
}

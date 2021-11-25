<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Carbon\Carbon;
use Dawson\Youtube\Facades\Youtube;
use Illuminate\Support\Facades\Storage;

class ScanVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:youtube';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đẩy video lên youtube';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dataArr = Product::where('status', 5)->where('status_video', 2)->get();
        foreach ($dataArr as $value) {
            $str = $value->url_video;
            $re = '/.*[^-\w]([-\w]{25,})[^-\w]?.*/';
            preg_match($re, $str, $matches, PREG_OFFSET_CAPTURE, 0);
            try {
                $contents = file_get_contents("https://drive.google.com/u/0/uc?id=" . $matches[1][0] . "&export=download&confirm=Otpd");
                $name = Carbon::now()->format('Y-m-d') . '-' . uniqid() . '.mp4';
                Storage::put('videos/' . $name, $contents);
                $path = storage_path('app/videos/' . $name);
                Youtube::upload(storage_path('app/videos/' . $name), [
                    'title'       => "Mini",
                    //   $value->name,
                    'description' => "Dự án: "
                    // .$value->name."- Ngày xuất bản: ".Carbon::now()->format('Y-m-d'),
                ]);


                $product = Product::find($value->id);
                $product->status_video = 3;

                $product->save();
                unlink($path); 
            } catch (\Exception $ex) {
                dd($ex->getMessage());
            }
        }
        //            
    }
}

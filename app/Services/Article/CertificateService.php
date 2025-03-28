<?php

namespace App\Services\Article;

use App\Enums\ArticleStatusEnums;
use App\Enums\ArticleTypeEnums;
use App\Enums\CertificateEnums;
use App\Models\Article;
use App\Models\Certificate;
use App\Models\CertificateType;
use App\Services\Base\Service;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as FacadesLog;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Typography\FontFactory;
use Log;
use Storage;
use Str;
use ValueError;

class CertificateService extends Service
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @param [type] $getting_type - 0 получение сертификатов, 1 получение списка сертификатов (certificate_types) пользователей
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function get(Request $request, int $getting_type)
    {
        $query = $getting_type == 0 ? $this->getCertificates($request) : $this->getCertificateTypes($request);

        return $query;
    }

    private static function getCertificates(Request $request)
    {

        $user = Auth::guard('sanctum')->user();
        $query = $user->certificates();


        if ($search = $request->query('search')) {
            $query->whereHas('article', function ($query) use ($search) {
                $query->whereRaw("title LIKE ?", ["%{$search}%"]);
            });
        }

        return $query;
    }
    private static function getCertificateTypes(Request $request)
    {
        $query = CertificateType::query();

        if ($request->my == 1) {
            $user = Auth::guard('sanctum')->user();
            $query = $user->certificate_types();
        }

        if ($request->has('type')) {
            $type = CertificateEnums::tryFrom((int) $request->query('type'));
            $query = CertificateType::where('status', $type);
        }

        if ($search = $request->query('search')) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhereHas('user', function ($query) use ($search) {
                    $query->whereRaw("CONCAT(first_name, ' ', COALESCE(surname, '')) LIKE ?", ["%{$search}%"]);
                });
        }

        return $query;
    }

    public function modernImage(CertificateType $certificateType, UploadedFile $file): string
    {
        $positions = [
            json_decode($certificateType->title_position, true),
            json_decode($certificateType->date_position, true),
            json_decode($certificateType->logo_position, true)
        ];

        $image = ImageManager::gd()->read($file);
        foreach ($positions as $position) {
            $image->text($this->getCertificateLabel($position['type']), $position['x'], $position['y'], function (FontFactory $font) use ($position) {
                $font->filename('./fonts/JetBrainsMono-Regular.ttf');
                $font->size($position['size']);
                $font->color($position['color'] ?? 'fff');
                $font->align('left');
                $font->valign('top');
            });
        }

        $imagePath = 'layout_previews/' . Str::random() . '.' . $file->getClientOriginalExtension();
        Storage::put(
            'public/' . $imagePath,
            $image->encodeByExtension($file->getClientOriginalExtension(), quality: 70)
        );

        return $imagePath;
    }

    private static function getCertificateLabel($type)
    {
        switch ($type) {
            case 'logo':
                return 'Evollt School';
            case 'date':
                return '01.01.2025';
            case 'title':
                return 'Иванов Иван Иванович';
        }
    }
}

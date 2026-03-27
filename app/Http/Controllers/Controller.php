<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $statusCode;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respondWithError($message = null)
    {
        return response()->json(
            ['success' => false, 'msg' => $message]
        );
    }

    /**
     * Returns a Unauthorized response.
     *
     * @param  string  $message
     * @return Response
     */
    public function respondUnauthorized($message = 'Unauthorized action.')
    {
        return $this->setStatusCode(403)
            ->respondWithError($message);
    }

    /**
     * Returns a went wrong response.
     *
     * @param  object  $exception  = null
     * @return Response
     */
    public function respondWentWrong($exception = null)
    {
        // If debug is enabled then send exception message
        $message = (config('app.debug') && is_object($exception)) ? 'File:'.$exception->getFile().'Line:'.$exception->getLine().'Message:'.$exception->getMessage() : __('messages.something_went_wrong');

        // TODO: show exception error message when error is enabled.
        return $this->setStatusCode(200)
            ->respondWithError($message);
    }

    /**
     * Returns a 200 response.
     *
     * @param  object  $message  = null
     * @return Response
     */
    public function respondSuccess($message = null, $additional_data = [])
    {
        $message = is_null($message) ? __('lang_v.success') : $message;
        $data = ['success' => true, 'msg' => $message];

        if (! empty($additional_data)) {
            $data = array_merge($data, $additional_data);
        }

        return $this->respond($data);
    }

    /**
     * Returns a 200 response.
     *
     * @param  array  $data
     * @return Response
     */
    public function respond($data)
    {
        return response()->json($data);
    }

    /**
     * Returns new mpdf instance
     *
     * @param  string  $orientation  'P' for portrait (default), 'L' for landscape
     * @return Mpdf
     */
    public function getMpdf($orientation = 'P')
    {
        // Load default font directories and font data
        $defaultConfig = (new ConfigVariables)->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables)->getDefaults();
        $fontData = $defaultFontConfig['fontdata'] ?? []; // ensure array

        // Create mPDF instance
        $mpdf = new Mpdf([
            'tempDir' => public_path('uploads/temp'),
            'mode' => 'utf-8',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'useSubstitutions' => true,
            'orientation' => $orientation,
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'), // your custom fonts directory
            ]),
            'fontdata' => $fontData + [
                'notosansbengali' => [
                    'R' => 'NotoSansBengali-Regular.ttf',
                    'B' => 'NotoSansBengali-Bold.ttf',
                ],
            ],
            'default_font' => 'notosansbengali',
        ]);

        // RTL support if user language is RTL
        if (auth()->check() && in_array(auth()->user()->language, config('constants.langs_rtl'))) {
            $mpdf->SetDirectionality('rtl');
        }

        // Global CSS for fake bold if bold glyph missing
        $mpdf->WriteHTML('
        <style>
            body { font-family: notosansbengali; font-size: 14pt; }
            b, strong {
                font-family: notosansbengali !important;
                font-weight: normal !important; /* avoid missing bold glyphs */
                text-shadow: 0.3px 0 0 currentColor,
                             -0.3px 0 0 currentColor; /* fake bolding */
            }
        </style>
    ', HTMLParserMode::HEADER_CSS);

        return $mpdf;
    }
}

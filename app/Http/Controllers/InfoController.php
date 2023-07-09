<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\ProcessAddress;
use App\Models\InternetProtocolAddress;
use GeoIp2\Exception\AddressNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use LaravelJsonApi\Core\Document\Error;
use LaravelJsonApi\Core\Responses\ErrorResponse;
use Throwable;

final class InfoController extends Controller
{
    public function __invoke(Request $request, string $address)
    {
        try {
            if (InternetProtocolAddress::findByAddress($address)) {
                $address = InternetProtocolAddress::findByAddress($address);
            } else {
                $address = ProcessAddress::execute($address === 'me' ? $request->ip() : $address);
            }

            return Redirect::route('v1.internet-protocol-addresses.show', $address);
        } catch (Throwable $th) {
            if ($th instanceof AddressNotFoundException) {
                return ErrorResponse::make(
                    Error::make()
                        ->setStatus(404)
                        ->setDetail($th->getMessage()),
                );
            }

            return ErrorResponse::make(
                Error::make()
                    ->setStatus(500)
                    ->setDetail($th->getMessage()),
            );
        }
    }
}

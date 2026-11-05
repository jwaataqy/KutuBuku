@props(['value']){{ $value==0 ? 'Gratis' : 'Rp '.number_format($value,0,',','.') }}

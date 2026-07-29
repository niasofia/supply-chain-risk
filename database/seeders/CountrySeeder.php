<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            // Asia Tenggara
            ["code" => "id", "name" => "Indonesia", "port_name" => "Tanjung Priok, Jakarta", "lat" => -6.1018, "lng" => 106.8824],
            ["code" => "my", "name" => "Malaysia", "port_name" => "Port Klang", "lat" => 3.0000, "lng" => 101.4000],
            ["code" => "sg", "name" => "Singapore", "port_name" => "Port of Singapore", "lat" => 1.2644, "lng" => 103.8400],
            ["code" => "th", "name" => "Thailand", "port_name" => "Port of Bangkok", "lat" => 13.7563, "lng" => 100.5018],
            ["code" => "vn", "name" => "Vietnam", "port_name" => "Port of Ho Chi Minh", "lat" => 10.7769, "lng" => 106.7009],
            ["code" => "ph", "name" => "Philippines", "port_name" => "Port of Manila", "lat" => 14.5995, "lng" => 120.9842],
            ["code" => "bn", "name" => "Brunei", "port_name" => "Muara Port", "lat" => 5.0350, "lng" => 115.0657],
            ["code" => "kh", "name" => "Cambodia", "port_name" => "Sihanoukville Port", "lat" => 10.6253, "lng" => 103.5283],
            ["code" => "mm", "name" => "Myanmar", "port_name" => "Port of Yangon", "lat" => 16.8409, "lng" => 96.1735],
            ["code" => "la", "name" => "Laos", "port_name" => "Thanaleng Dry Port", "lat" => 17.9389, "lng" => 102.6331],
            ["code" => "tl", "name" => "Timor-Leste", "port_name" => "Port of Dili", "lat" => -8.5569, "lng" => 125.5603],

            // Asia Timur & Selatan
            ["code" => "cn", "name" => "China", "port_name" => "Port of Shanghai", "lat" => 31.2304, "lng" => 121.4737],
            ["code" => "jp", "name" => "Japan", "port_name" => "Port of Tokyo", "lat" => 35.6762, "lng" => 139.6503],
            ["code" => "kr", "name" => "South Korea", "port_name" => "Port of Busan", "lat" => 35.1796, "lng" => 129.0756],
            ["code" => "tw", "name" => "Taiwan", "port_name" => "Port of Kaohsiung", "lat" => 22.6273, "lng" => 120.3014],
            ["code" => "hk", "name" => "Hong Kong", "port_name" => "Port of Hong Kong", "lat" => 22.3193, "lng" => 114.1694],
            ["code" => "in", "name" => "India", "port_name" => "JNPT Mumbai", "lat" => 18.9440, "lng" => 72.9490],
            ["code" => "pk", "name" => "Pakistan", "port_name" => "Port of Karachi", "lat" => 24.8607, "lng" => 67.0011],
            ["code" => "bd", "name" => "Bangladesh", "port_name" => "Port of Chittagong", "lat" => 22.3569, "lng" => 91.7832],
            ["code" => "lk", "name" => "Sri Lanka", "port_name" => "Port of Colombo", "lat" => 6.9271, "lng" => 79.8612],

            // Timur Tengah
            ["code" => "ae", "name" => "United Arab Emirates", "port_name" => "Port of Jebel Ali", "lat" => 25.0143, "lng" => 55.0630],
            ["code" => "sa", "name" => "Saudi Arabia", "port_name" => "Jeddah Islamic Port", "lat" => 21.4858, "lng" => 39.1925],
            ["code" => "qa", "name" => "Qatar", "port_name" => "Hamad Port", "lat" => 24.9378, "lng" => 51.5591],
            ["code" => "om", "name" => "Oman", "port_name" => "Port of Salalah", "lat" => 16.9427, "lng" => 54.0041],
            ["code" => "tr", "name" => "Turkey", "port_name" => "Port of Ambarli", "lat" => 40.9781, "lng" => 28.7126],
            ["code" => "ir", "name" => "Iran", "port_name" => "Port of Bandar Abbas", "lat" => 27.1832, "lng" => 56.2666],
            ["code" => "il", "name" => "Israel", "port_name" => "Port of Haifa", "lat" => 32.7940, "lng" => 34.9896],
            ["code" => "jo", "name" => "Jordan", "port_name" => "Port of Aqaba", "lat" => 29.5321, "lng" => 35.0024],
            ["code" => "kw", "name" => "Kuwait", "port_name" => "Port of Shuaiba", "lat" => 29.0333, "lng" => 48.1333],
            ["code" => "bh", "name" => "Bahrain", "port_name" => "Khalifa Bin Salman Port", "lat" => 26.2285, "lng" => 50.6556],

            // Eropa
            ["code" => "nl", "name" => "Netherlands", "port_name" => "Port of Rotterdam", "lat" => 51.9244, "lng" => 4.4777],
            ["code" => "be", "name" => "Belgium", "port_name" => "Port of Antwerp", "lat" => 51.2194, "lng" => 4.4025],
            ["code" => "de", "name" => "Germany", "port_name" => "Port of Hamburg", "lat" => 53.5511, "lng" => 9.9937],
            ["code" => "gb", "name" => "United Kingdom", "port_name" => "Port of Felixstowe", "lat" => 51.9566, "lng" => 1.3513],
            ["code" => "fr", "name" => "France", "port_name" => "Port of Le Havre", "lat" => 49.4944, "lng" => 0.1079],
            ["code" => "es", "name" => "Spain", "port_name" => "Port of Valencia", "lat" => 39.4699, "lng" => -0.3763],
            ["code" => "it", "name" => "Italy", "port_name" => "Port of Genoa", "lat" => 44.4056, "lng" => 8.9463],
            ["code" => "gr", "name" => "Greece", "port_name" => "Port of Piraeus", "lat" => 37.9475, "lng" => 23.6375],
            ["code" => "pt", "name" => "Portugal", "port_name" => "Port of Sines", "lat" => 37.9556, "lng" => -8.8710],
            ["code" => "pl", "name" => "Poland", "port_name" => "Port of Gdansk", "lat" => 54.3520, "lng" => 18.6466],
            ["code" => "se", "name" => "Sweden", "port_name" => "Port of Gothenburg", "lat" => 57.7089, "lng" => 11.9746],
            ["code" => "no", "name" => "Norway", "port_name" => "Port of Oslo", "lat" => 59.9139, "lng" => 10.7522],
            ["code" => "dk", "name" => "Denmark", "port_name" => "Port of Copenhagen", "lat" => 55.6761, "lng" => 12.5683],
            ["code" => "fi", "name" => "Finland", "port_name" => "Port of Helsinki", "lat" => 60.1699, "lng" => 24.9384],
            ["code" => "ie", "name" => "Ireland", "port_name" => "Port of Dublin", "lat" => 53.3498, "lng" => -6.2603],
            ["code" => "ch", "name" => "Switzerland", "port_name" => "Basel Port", "lat" => 47.5596, "lng" => 7.5886],
            ["code" => "at", "name" => "Austria", "port_name" => "Vienna Terminal", "lat" => 48.2082, "lng" => 16.3738],
            ["code" => "ru", "name" => "Russia", "port_name" => "Port of St. Petersburg", "lat" => 59.9343, "lng" => 30.3351],
            ["code" => "ua", "name" => "Ukraine", "port_name" => "Port of Odessa", "lat" => 46.4825, "lng" => 30.7233],
            ["code" => "ro", "name" => "Romania", "port_name" => "Port of Constanta", "lat" => 44.1792, "lng" => 28.6548],
            ["code" => "cz", "name" => "Czech Republic", "port_name" => "Prague Hub", "lat" => 50.0755, "lng" => 14.4378],
            ["code" => "hu", "name" => "Hungary", "port_name" => "Budapest Freeport", "lat" => 47.4979, "lng" => 19.0402],

            // Amerika Utara
            ["code" => "us", "name" => "United States", "port_name" => "Port of Los Angeles", "lat" => 33.7405, "lng" => -118.2786],
            ["code" => "ca", "name" => "Canada", "port_name" => "Port of Vancouver", "lat" => 49.2827, "lng" => -123.1207],
            ["code" => "mx", "name" => "Mexico", "port_name" => "Port of Manzanillo", "lat" => 19.0531, "lng" => -104.3159],

            // Amerika Latin
            ["code" => "br", "name" => "Brazil", "port_name" => "Port of Santos", "lat" => -23.9608, "lng" => -46.3336],
            ["code" => "ar", "name" => "Argentina", "port_name" => "Port of Buenos Aires", "lat" => -34.6037, "lng" => -58.3816],
            ["code" => "cl", "name" => "Chile", "port_name" => "Port of Valparaiso", "lat" => -33.0472, "lng" => -71.6127],
            ["code" => "co", "name" => "Colombia", "port_name" => "Port of Cartagena", "lat" => 10.3910, "lng" => -75.4794],
            ["code" => "pe", "name" => "Peru", "port_name" => "Port of Callao", "lat" => -12.0566, "lng" => -77.1181],
            ["code" => "ec", "name" => "Ecuador", "port_name" => "Port of Guayaquil", "lat" => -2.1894, "lng" => -79.8891],
            ["code" => "uy", "name" => "Uruguay", "port_name" => "Port of Montevideo", "lat" => -34.9011, "lng" => -56.1645],
            ["code" => "pa", "name" => "Panama", "port_name" => "Port of Balboa", "lat" => 8.9514, "lng" => -79.5647],
            ["code" => "cr", "name" => "Costa Rica", "port_name" => "Port of Caldera", "lat" => 9.9546, "lng" => -84.7176],
            ["code" => "cu", "name" => "Cuba", "port_name" => "Port of Havana", "lat" => 23.1136, "lng" => -82.3666],

            // Afrika
            ["code" => "za", "name" => "South Africa", "port_name" => "Port of Durban", "lat" => -29.8587, "lng" => 31.0218],
            ["code" => "eg", "name" => "Egypt", "port_name" => "Port Said", "lat" => 31.2653, "lng" => 32.3019],
            ["code" => "ng", "name" => "Nigeria", "port_name" => "Lagos Port Complex", "lat" => 6.4550, "lng" => 3.3841],
            ["code" => "ke", "name" => "Kenya", "port_name" => "Port of Mombasa", "lat" => -4.0435, "lng" => 39.6682],
            ["code" => "ma", "name" => "Morocco", "port_name" => "Tanger Med Port", "lat" => 35.8897, "lng" => -5.5233],
            ["code" => "gh", "name" => "Ghana", "port_name" => "Port of Tema", "lat" => 5.6698, "lng" => 0.0166],
            ["code" => "tz", "name" => "Tanzania", "port_name" => "Port of Dar es Salaam", "lat" => -6.7924, "lng" => 39.2083],
            ["code" => "dz", "name" => "Algeria", "port_name" => "Port of Algiers", "lat" => 36.7538, "lng" => 3.0588],
            ["code" => "tn", "name" => "Tunisia", "port_name" => "Port of Rades", "lat" => 36.7681, "lng" => 10.2742],
            ["code" => "ao", "name" => "Angola", "port_name" => "Port of Luanda", "lat" => -8.8399, "lng" => 13.2894],

            // Oseania
            ["code" => "au", "name" => "Australia", "port_name" => "Port of Melbourne", "lat" => -37.8136, "lng" => 144.9631],
            ["code" => "nz", "name" => "New Zealand", "port_name" => "Port of Auckland", "lat" => -36.8485, "lng" => 174.7633],
            ["code" => "fj", "name" => "Fiji", "port_name" => "Port of Suva", "lat" => -18.1416, "lng" => 178.4415],
            ["code" => "pg", "name" => "Papua New Guinea", "port_name" => "Port Moresby", "lat" => -9.4438, "lng" => 147.1803]
        ];

        foreach ($countries as $c) {
            Country::updateOrCreate(["code" => $c["code"]], $c);
        }
    }
}
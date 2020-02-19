<?php

use App\City;
use App\Lecture;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
         $this->call(CityTableSeeder::class);
         $this->call(LectureTableSeeder::class);
    }
}

class CityTableSeeder extends Seeder {

    public function run() {
        DB::table(DB_CITY_TABLE)->truncate();

        $json = json_decode('{"Adana":["Hepsi","Aladağ","Ceyhan","Çukurova","Feke","İmamoğlu","Karaisalı","Karataş","Kozan","Pozantı","Saimbeyli","Sarıçam","Seyhan","Tufanbeyli","Yumurtalık","Yüreğir"],"Adıyaman":["Hepsi","Besni","Çelikhan","Gerger","Gölbaşı","Kahta","Merkez","Samsat","Sincik","Tut"],"Afyonkarahisar":["Hepsi","Başmakçı","Bayat","Bolvadin","Çay","Çobanlar","Dazkırı","Dinar","Emirdağ","Evciler","Hocalar","İhsaniye","İscehisar","Kızılören","Merkez","Sandıklı","Sinanpaşa","Sultandağı","Şuhut"],"Ağrı":["Hepsi","Diyadin","Doğubayazıt","Eleşkirt","Hamur","Merkez","Patnos","Taşlıçay","Tutak"],"Amasya":["Hepsi","Göynücek","Gümüşhacıköy","Hamamözü","Merkez","Merzifon","Suluova","Taşova"],"Ankara":["Hepsi","Altındağ","Ayaş","Bala","Beypazarı","Çamlıdere","Çankaya","Çubuk","Elmadağ","Güdül","Haymana","Kalecik","Kızılcahamam","Nallıhan","Polatlı","Şereflikoçhisar","Yenimahalle","Gölbaşı","Keçiören","Mamak","Sincan","Kazan","Akyurt","Etimesgut","Evren","Pursaklar"],"Antalya":["Hepsi","Akseki","Alanya","Elmalı","Finike","Gazipaşa","Gündoğmuş","Kaş","Korkuteli","Kumluca","Manavgat","Serik","Demre","İbradı","Kemer","Aksu","Döşemealtı","Kepez","Konyaaltı","Muratpaşa"],"Artvin":["Hepsi","Ardanuç","Arhavi","Merkez","Borçka","Hopa","Şavşat","Yusufeli","Murgul"],"Aydın":["Hepsi","Merkez","Bozdoğan","Efeler","Çine","Germencik","Karacasu","Koçarlı","Kuşadası","Kuyucak","Nazilli","Söke","Sultanhisar","Yenipazar","Buharkent","İncirliova","Karpuzlu","Köşk","Didim"],"Balıkesir":["Hepsi","Altıeylül","Ayvalık","Merkez","Balya","Bandırma","Bigadiç","Burhaniye","Dursunbey","Edremit","Erdek","Gönen","Havran","İvrindi","Karesi","Kepsut","Manyas","Savaştepe","Sındırgı","Gömeç","Susurluk","Marmara"],"Bilecik":["Hepsi","Merkez","Bozüyük","Gölpazarı","Osmaneli","Pazaryeri","Söğüt","Yenipazar","İnhisar"],"Bingöl":["Hepsi","Merkez","Genç","Karlıova","Kiğı","Solhan","Adaklı","Yayladere","Yedisu"],"Bitlis":["Hepsi","Adilcevaz","Ahlat","Merkez","Hizan","Mutki","Tatvan","Güroymak"],"Bolu":["Hepsi","Merkez","Gerede","Göynük","Kıbrıscık","Mengen","Mudurnu","Seben","Dörtdivan","Yeniçağa"],"Burdur":["Hepsi","Ağlasun","Bucak","Merkez","Gölhisar","Tefenni","Yeşilova","Karamanlı","Kemer","Altınyayla","Çavdır","Çeltikçi"],"Bursa":["Hepsi","Gemlik","İnegöl","İznik","Karacabey","Keles","Mudanya","Mustafakemalpaşa","Orhaneli","Orhangazi","Yenişehir","Büyükorhan","Harmancık","Nilüfer","Osmangazi","Yıldırım","Gürsu","Kestel"],"Çanakkale":["Hepsi","Ayvacık","Bayramiç","Biga","Bozcaada","Çan","Merkez","Eceabat","Ezine","Gelibolu","Gökçeada","Lapseki","Yenice"],"Çankırı":["Hepsi","Merkez","Çerkeş","Eldivan","Ilgaz","Kurşunlu","Orta","Şabanözü","Yapraklı","Atkaracalar","Kızılırmak","Bayramören","Korgun"],"Çorum":["Hepsi","Alaca","Bayat","Merkez","İskilip","Kargı","Mecitözü","Ortaköy","Osmancık","Sungurlu","Boğazkale","Uğurludağ","Dodurga","Laçin","Oğuzlar"],"Denizli":["Hepsi","Acıpayam","Buldan","Çal","Çameli","Çardak","Çivril","Merkez","Merkezefendi","Pamukkale","Güney","Kale","Sarayköy","Tavas","Babadağ","Bekilli","Honaz","Serinhisar","Baklan","Beyağaç","Bozkurt"],"Diyarbakır":["Hepsi","Kocaköy","Çermik","Çınar","Çüngüş","Dicle","Ergani","Hani","Hazro","Kulp","Lice","Silvan","Eğil","Bağlar","Kayapınar","Sur","Yenişehir","Bismil"],"Edirne":["Hepsi","Merkez","Enez","Havsa","İpsala","Keşan","Lalapaşa","Meriç","Uzunköprü","Süloğlu"],"Elazığ":["Hepsi","Ağın","Baskil","Merkez","Karakoçan","Keban","Maden","Palu","Sivrice","Arıcak","Kovancılar","Alacakaya"],"Erzincan":["Hepsi","Çayırlı","Merkez","İliç","Kemah","Kemaliye","Refahiye","Tercan","Üzümlü","Otlukbeli"],"Erzurum":["Hepsi","Aşkale","Çat","Hınıs","Horasan","İspir","Karayazı","Narman","Oltu","Olur","Pasinler","Şenkaya","Tekman","Tortum","Karaçoban","Uzundere","Pazaryolu","Köprüköy","Palandöken","Yakutiye","Aziziye"],"Eskişehir":["Hepsi","Çifteler","Mahmudiye","Mihalıççık","Sarıcakaya","Seyitgazi","Sivrihisar","Alpu","Beylikova","İnönü","Günyüzü","Han","Mihalgazi","Odunpazarı","Tepebaşı"],"Gaziantep":["Hepsi","Araban","İslahiye","Nizip","Oğuzeli","Yavuzeli","Şahinbey","Şehitkamil","Karkamış","Nurdağı"],"Giresun":["Hepsi","Alucra","Bulancak","Dereli","Espiye","Eynesil","Merkez","Görele","Keşap","Şebinkarahisar","Tirebolu","Piraziz","Yağlıdere","Çamoluk","Çanakçı","Doğankent","Güce"],"Gümüşhane":["Hepsi","Merkez","Kelkit","Şiran","Torul","Köse","Kürtün"],"Hakkari":["Hepsi","Çukurca","Merkez","Şemdinli","Yüksekova"],"Hatay":["Hepsi","Altınözü","Arsuz","Defne","Dörtyol","Hassa","Antakya","İskenderun","Kırıkhan","Payas","Reyhanlı","Samandağ","Yayladağı","Erzin","Belen","Kumlu"],"Isparta":["Hepsi","Atabey","Eğirdir","Gelendost","Merkez","Keçiborlu","Senirkent","Sütçüler","Şarkikaraağaç","Uluborlu","Yalvaç","Aksu","Gönen","Yenişarbademli"],"Mersin":["Hepsi","Anamur","Erdemli","Gülnar","Mut","Silifke","Tarsus","Aydıncık","Bozyazı","Çamlıyayla","Akdeniz","Mezitli","Toroslar","Yenişehir"],"Istanbul":["Hepsi","Adalar","Bakırköy","Beşiktaş","Beykoz","Beyoğlu","Çatalca","Eyüp","Fatih","Gaziosmanpaşa","Kadıköy","Kartal","Sarıyer","Silivri","Şile","Şişli","Üsküdar","Zeytinburnu","Büyükçekmece","Kağıthane","Küçükçekmece","Pendik","Ümraniye","Bayrampaşa","Avcılar","Bağcılar","Bahçelievler","Güngören","Maltepe","Sultanbeyli","Tuzla","Esenler","Arnavutköy","Ataşehir","Başakşehir","Beylikdüzü","Çekmeköy","Esenyurt","Sancaktepe","Sultangazi"],"Izmir":["Hepsi","Aliağa","Bayındır","Bergama","Bornova","Çeşme","Dikili","Foça","Karaburun","Karşıyaka","Kemalpaşa","Kınık","Kiraz","Menemen","Ödemiş","Seferihisar","Selçuk","Tire","Torbalı","Urla","Beydağ","Buca","Konak","Menderes","Balçova","Çiğli","Gaziemir","Narlıdere","Güzelbahçe","Bayraklı","Karabağlar"],"Kars":["Hepsi","Arpaçay","Digor","Kağızman","Merkez","Sarıkamış","Selim","Susuz","Akyaka"],"Kastamonu":["Hepsi","Abana","Araç","Azdavay","Bozkurt","Cide","Çatalzeytin","Daday","Devrekani","İnebolu","Merkez","Küre","Taşköprü","Tosya","İhsangazi","Pınarbaşı","Şenpazar","Ağlı","Doğanyurt","Hanönü","Seydiler"],"Kayseri":["Hepsi","Bünyan","Develi","Felahiye","İncesu","Pınarbaşı","Sarıoğlan","Sarız","Tomarza","Yahyalı","Yeşilhisar","Akkışla","Talas","Kocasinan","Melikgazi","Hacılar","Özvatan"],"Kırklareli":["Hepsi","Babaeski","Demirköy","Merkez","Kofçaz","Lüleburgaz","Pehlivanköy","Pınarhisar","Vize"],"Kırşehir":["Hepsi","Çiçekdağı","Kaman","Merkez","Mucur","Akpınar","Akçakent","Boztepe"],"Kocaeli":["Hepsi","Gebze","Gölcük","Kandıra","Karamürsel","Körfez","Derince","Başiskele","Çayırova","Darıca","Dilovası","İzmit","Kartepe"],"Konya":["Hepsi","Akşehir","Beyşehir","Bozkır","Cihanbeyli","Çumra","Doğanhisar","Ereğli","Hadim","Ilgın","Kadınhanı","Karapınar","Kulu","Sarayönü","Seydişehir","Yunak","Akören","Altınekin","Derebucak","Hüyük","Karatay","Meram","Selçuklu","Taşkent","Ahırlı","Çeltik","Derbent","Emirgazi","Güneysınır","Halkapınar","Tuzlukçu","Yalıhüyük"],"Kütahya":["Hepsi","Altıntaş","Domaniç","Emet","Gediz","Merkez","Simav","Tavşanlı","Aslanapa","Dumlupınar","Hisarcık","Şaphane","Çavdarhisar","Pazarlar"],"Malatya":["Hepsi","Akçadağ","Arapgir","Arguvan","Darende","Doğanşehir","Hekimhan","Merkez","Pütürge","Yeşilyurt","Battalgazi","Doğanyol","Kale","Kuluncak","Yazıhan"],"Manisa":["Hepsi","Akhisar","Alaşehir","Demirci","Gördes","Kırkağaç","Kula","Merkez","Salihli","Sarıgöl","Saruhanlı","Selendi","Soma","Şehzadeler","Yunusemre","Turgutlu","Ahmetli","Gölmarmara","Köprübaşı"],"Kahramanmaraş":["Hepsi","Afşin","Andırın","Dulkadiroğlu","Onikişubat","Elbistan","Göksun","Merkez","Pazarcık","Türkoğlu","Çağlayancerit","Ekinözü","Nurhak"],"Mardin":["Hepsi","Derik","Kızıltepe","Artuklu","Merkez","Mazıdağı","Midyat","Nusaybin","Ömerli","Savur","Dargeçit","Yeşilli"],"Muğla":["Hepsi","Bodrum","Datça","Fethiye","Köyceğiz","Marmaris","Menteşe","Milas","Ula","Yatağan","Dalaman","Seydikemer","Ortaca","Kavaklıdere"],"Muş":["Hepsi","Bulanık","Malazgirt","Merkez","Varto","Hasköy","Korkut"],"Nevşehir":["Hepsi","Avanos","Derinkuyu","Gülşehir","Hacıbektaş","Kozaklı","Merkez","Ürgüp","Acıgöl"],"Niğde":["Hepsi","Bor","Çamardı","Merkez","Ulukışla","Altunhisar","Çiftlik"],"Ordu":["Hepsi","Akkuş","Altınordu","Aybastı","Fatsa","Gölköy","Korgan","Kumru","Mesudiye","Perşembe","Ulubey","Ünye","Gülyalı","Gürgentepe","Çamaş","Çatalpınar","Çaybaşı","İkizce","Kabadüz","Kabataş"],"Rize":["Hepsi","Ardeşen","Çamlıhemşin","Çayeli","Fındıklı","İkizdere","Kalkandere","Pazar","Merkez","Güneysu","Derepazarı","Hemşin","İyidere"],"Sakarya":["Hepsi","Akyazı","Geyve","Hendek","Karasu","Kaynarca","Sapanca","Kocaali","Pamukova","Taraklı","Ferizli","Karapürçek","Söğütlü","Adapazarı","Arifiye","Erenler","Serdivan"],"Samsun":["Hepsi","Alaçam","Bafra","Çarşamba","Havza","Kavak","Ladik","Terme","Vezirköprü","Asarcık","Ondokuzmayıs","Salıpazarı","Tekkeköy","Ayvacık","Yakakent","Atakum","Canik","İlkadım"],"Siirt":["Hepsi","Baykan","Eruh","Kurtalan","Pervari","Merkez","Şirvan","Tillo"],"Sinop":["Hepsi","Ayancık","Boyabat","Durağan","Erfelek","Gerze","Merkez","Türkeli","Dikmen","Saraydüzü"],"Sivas":["Hepsi","Divriği","Gemerek","Gürün","Hafik","İmranlı","Kangal","Koyulhisar","Merkez","Suşehri","Şarkışla","Yıldızeli","Zara","Akıncılar","Altınyayla","Doğanşar","Gölova","Ulaş"],"Tekirdağ":["Hepsi","Çerkezköy","Çorlu","Ergene","Hayrabolu","Malkara","Muratlı","Saray","Süleymanpaşa","Kapaklı","Şarköy","Marmaraereğlisi"],"Tokat":["Hepsi","Almus","Artova","Erbaa","Niksar","Reşadiye","Merkez","Turhal","Zile","Pazar","Yeşilyurt","Başçiftlik","Sulusaray"],"Trabzon":["Hepsi","Akçaabat","Araklı","Arsin","Çaykara","Maçka","Of","Ortahisar","Sürmene","Tonya","Vakfıkebir","Yomra","Beşikdüzü","Şalpazarı","Çarşıbaşı","Dernekpazarı","Düzköy","Hayrat","Köprübaşı"],"Tunceli":["Hepsi","Çemişgezek","Hozat","Mazgirt","Nazımiye","Ovacık","Pertek","Pülümür","Merkez"],"Şanlıurfa":["Hepsi","Akçakale","Birecik","Bozova","Ceylanpınar","Eyyübiye","Halfeti","Haliliye","Hilvan","Karaköprü","Siverek","Suruç","Viranşehir","Harran"],"Uşak":["Hepsi","Banaz","Eşme","Karahallı","Sivaslı","Ulubey","Merkez"],"Van":["Hepsi","Başkale","Çatak","Erciş","Gevaş","Gürpınar","İpekyolu","Muradiye","Özalp","Tuşba","Bahçesaray","Çaldıran","Edremit","Saray"],"Yozgat":["Hepsi","Akdağmadeni","Boğazlıyan","Çayıralan","Çekerek","Sarıkaya","Sorgun","Şefaatli","Yerköy","Merkez","Aydıncık","Çandır","Kadışehri","Saraykent","Yenifakılı"],"Zonguldak":["Hepsi","Çaycuma","Devrek","Ereğli","Merkez","Alaplı","Gökçebey"],"Aksaray":["Hepsi","Ağaçören","Eskil","Gülağaç","Güzelyurt","Merkez","Ortaköy","Sarıyahşi"],"Bayburt":["Hepsi","Merkez","Aydıntepe","Demirözü"],"Karaman":["Hepsi","Ermenek","Merkez","Ayrancı","Kazımkarabekir","Başyayla","Sarıveliler"],"Kırıkkale":["Hepsi","Delice","Keskin","Merkez","Sulakyurt","Bahşili","Balışeyh","Çelebi","Karakeçili","Yahşihan"],"Batman":["Hepsi","Merkez","Beşiri","Gercüş","Kozluk","Sason","Hasankeyf"],"Şırnak":["Hepsi","Beytüşşebap","Cizre","İdil","Silopi","Merkez","Uludere","Güçlükonak"],"Bartın":["Hepsi","Merkez","Kurucaşile","Ulus","Amasra"],"Ardahan":["Hepsi","Merkez","Çıldır","Göle","Hanak","Posof","Damal"],"Iğdır":["Hepsi","Aralık","Merkez","Tuzluca","Karakoyunlu"],"Yalova":["Hepsi","Merkez","Altınova","Armutlu","Çınarcık","Çiftlikköy","Termal"],"Karabük":["Hepsi","Eflani","Eskipazar","Merkez","Ovacık","Safranbolu","Yenice"],"Kilis":["Hepsi","Merkez","Elbeyli","Musabeyli","Polateli"],"Osmaniye":["Hepsi","Bahçe","Kadirli","Merkez","Düziçi","Hasanbeyli","Sumbas","Toprakkale"],"Düzce":["Hepsi","Akçakoca","Merkez","Yığılca","Cumayeri","Gölyaka","Çilimli","Gümüşova","Kaynaşlı"]}');
        foreach ($json as $city => $districts) {
            foreach ($districts as $district) {
                City::create([COUNTRY_CODE => COUNTRY_TURKEY_CODE, CITY_NAME => $city, DISTRICT_NAME => $district]);
            }
        }

    }

}

class LectureTableSeeder extends Seeder {

    public function run() {
        DB::table(DB_LECTURE_TABLE)->truncate();

        $json = json_decode('{"İlköğretim Takviye":["Tüm Konular","Bilgisayar","Bilgi Teknolojisi","Din Kültürü ve Ahlak Bilgisi","Drama","Düşünme","Fen Bilgisi","Fen ve Teknoloji","Görsel Sanatlar","Halk Oyunları","Hayat Bilgisi","İletişim ve Sunum","Matematik","Müzik","Okuma-Yazma","Satranç","Sosyal Bilgiler","Tarım","Tarih","Teknoloji ve Tasarım","Tiyatro","Trafik","Türkçe","Vatandaşlık","Yabancı Dil"],"Lise Takviye":[],"Üniversite Takviye":[],"Sınav Hazırlık":[],"Yabancı Dil":[],"Bilgisayar":[],"Müzik":[],"Spor":[],"Sanat":[],"Dans":[],"Kişisel Gelişim":[],"Direksiyon":[],"Özel Eğitim":[],"Oyun ve Hobi":[]}');
        foreach ($json as $lectureArea => $lectureThemes) {
            foreach ($lectureThemes as $lectureTheme) {
                Lecture::create([LECTURE_AREA => $lectureArea, LECTURE_THEME => $lectureTheme, AVERAGE_TRY => 0]);
            }
        }
    }
}

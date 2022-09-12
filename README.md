# ImgProcessor
## _Symfony Command for resize and upload images_

Testowa apka do resizowania i uploadu obrazków

## Features

- Zmiania rozmiaru obrazka podanego w argumencie komendy
- Upload miniaturki do skonfigurowanej destynacji

## Opis tech

Aplikacja składa się z dwóch serwisów. ImgResizer zajmujący się zmianą rozmiaru obrazka oraz ImgPusher zajmujący się uploadem miniaturki pod wcześniej zdefiniowaną destynację. Wymienione serwisy nasłuchują eventu ResizeAndPushImageEvent który jest wywoływany w symfonowej commandzie (OMG - powiedzieć po polsku łatwiej niż najpisać 'commandzie'). Serwisy został oparty o symfonowego messangera (z najprostszym synchronicznym transportem) aby umożliwić łatwą skalowalność i dać elastyczności. Przykłdowo jeśli w webowej części aplikacji chcielibyśmy przetworzyć obrazek nie trzeba korzystać z wywołań lini poleceń a wystarczy zgłosić dany event. Albo w przypadku potrzeby przetwarzania dużej ilości danych można łatwo je przetwarzać w osobnych komendach w tempie w jakim moga być skonsumowane.

## Tech requirements

- [PHP 8.1] - with GD lib
- [Composer]

## Installation

```
composer install
```

## Configuration

W pliku config/imgPusher/services.yaml zdefiniowane są przykładowe transportery czyli serwisy uploadujące pod wskazaną destynację. Każdy serwis o tagu 'app.img_pusher_transport zostanie' dołączony do kolekcji dostępnych transporterów. Alias serwisu np: 'local1' lub 'produkcja' to transport czyli drugi argument lini poleceń.

Przykładowo:

Transporter ImgPusher_Transport_LocalStorage1 i ImgPusher_Transport_LocalStorage2 potrzebują argumentu pierwszego - destynacji uploadu.

Transporter ImgPusher_Transport_DropboxProd potrzebuje tokenu do aplikacji dropbox i katalogu uploadu.

## Run

```
bin\console app:img-proc --help

bin\console app:img-proc \path\to\source\filename.jpg definedTransport
```

## Tests

```
bin/phpspec run
```

## Development

Aplikacja została tak zbudowana, aby otwartą na rozbudowę. Aby dodawać nowe destynacje pod które ma być uploadowany plik należy albo dopisać kolejny konfig w config/imgPusher/services.yaml jeśli bazujemy na istniejącym transporcie np:

```
ImgPusher_Transport_LocalStorage2:
    class: App\ImgPusherTransport\Transport\LocalFilesystem
    arguments:
      - '\any_dir\resized\'
      - '@Symfony\Component\Filesystem\Filesystem'
    tags:
      - { name: 'app.img_pusher_transport', alias: 'local2' }
```

lub stworzyć nowy transport, czyli klasę, którą będzie implementować interfejs ImgPusherTransport



## Co mnie zastanawia lub boli

Test resizera jest taki sobie. Trzeba by jeszcze sprawdzić czy zwracany box faktycznie zmienia rozmiary ale w tym celu należałoby już to robić na prawdziwym obrazku.

Nie podoba mi się, że definicje transporterów mają potrzebę konfigurowania clienta (drugi argument)

```
  ImgPusher_Transport_LocalStorage2:
    class: App\ImgPusherTransport\Transport\LocalFilesystem
    arguments:
      - '\resizedImages2\'
      - '@Symfony\Component\Filesystem\Filesystem' //TO BYM CHĘTNIE WYRZUCIŁ
    tags:
      - { name: 'app.img_pusher_transport', alias: 'local2' }
      
```

ale tak łatwiej było testować i mockować. Gdyby client był tworzony w kontruktorze np new Filesystem() lub new Client($authorizationToken) wtedy obraz jest bardziej klarowny i w konfigu transportu mamy tylko to co nas interesuje.
Chyba najlepszym wyjściem byłby dziedziczenie danego transportera po rodzicu, definiowanie go jako serwis i przy definicji wywołać 'calls': setClient() ale to też troche dziwne definiować abstrakcje jako serwis... Musiałbym przymyślec jeszcze, ten ból pojawił się pod koniec pracy :-)

Użyta libka do dropboxa nie jest szczególnie piękna, brałem co było pod ręką, ma wady bo wiele wyjątków z komunikacji z dropboxem wycisza. 
W produkcyjnej wersji trzeba by się zastanowić troche dłużej ale skoro działa i jak sądze nie to było celem ćwiczenia aby analizować dostępne libki lub pisać swoje - to zostawiam.

Nazewnictwo: imgRezier - ok, imgPuser - ok, ale ta część aplikacyjna która łączy oba serwisy: impProcessor - coś mi nie gra ale nic lepszego nie wymyśliłem :-)

## Docker

Nie zdążyłem :-( ostatnio nie zajmuję się dockerem, wyszedłem z prawy i czasu nie starczyło, potrzebowałbym dzień lub dwa żeby odświerzyć temat....


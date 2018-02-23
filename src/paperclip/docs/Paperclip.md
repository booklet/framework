# Paperclip

## Instalacja

W modelu w ktorym chcemy użyc dodajemy trait

`use NewPaperClipTrait;`

Dodaje on do meodelu dynamiczne metody takie jak (przykład dla preview):

`$this->preview()`
Zwraca tablcie danych o zalaczniku
preview_file_name, preview_file_size, preview_content_type, preview_updated_at

`$this->previewPath(), $this->previewPath('style_name')`
Zwraca sciezkę do pliku (parametr opcjonalny to nazwa stylu dla ktorego chcemy sciezkę, domyslnie original)

`$this->previewUrl(), $this->previewUrl('style_name')`
Zwraca pełny URL do pliku (parametr opcjonalny to nazwa stylu dla ktorego chcemy sciezkę, domyslnie original)

`$this->previewSave(array $file) or (string $file_path) without validation!`
Metoda wykorzystywana przy zapisywaniu nie korzystac z niej, uzywac save() na modelu

`$this->previewReprocess()`
Przetworzenie plikow (np. po zmianie wielkosci styli)

`$this->previewDestroy()`
Skasowanie załacznika, docelowo bedzie podpiete pod $model->destroy();


oraz

`const ALLOWED_CONTENT_TYPE = [
    'application/pdf' => ['pdf'],
    'application/postscript' => ['ai', 'eps', 'ps'],
    'image/svg+xml' => ['svg', 'svgz'],
    'image/gif' => ['gif'],
    'image/jpeg' => ['jpeg', 'jpg', 'jpe'],
    'image/png' => ['png'],
    'image/tiff' => ['tiff', 'tif'],
    'image/bmp' => ['bmp'],
];

public function hasAttachedFile()
{
    return [
        'file' => [
            'styles' => [
                'thumbnail' => '60x60>',
            ],
            'content_type' => self::ALLOWED_CONTENT_TYPE,
            'max_size' => 10485760, // 10 MB
        ],
    ];
}`

`60x60` - skaluje proporcjonalnie do zadanego wymiaru (zaden z bokow nie bedzie dluzsy niz 60px)
`60x60>` - to samo co powyzej ale tylko jesli grafika jest wieksza, mniejsze nie zostana powiekszone
`60x60#` - kropowanie grafiki centralnie

oraz dodajemy specjalny atrybut o nazwie załaczniki (attachment)

`public function specialPropertis()
{
    return ['file'];
}`


## Uzycie

`$model_item = new ExampleModel();
$model_item->file = $file;
$model_item->save();
`

Jako zmienna $file mozemy przekazac:
- tablice `$_FILES` (jesli tabela zawiera wiecej niz jeden plik, zostanie uzyty tylko pierwszy)
- tablice files w postaci znormalizowanej, (jesli tabela zawiera wiecej niz jeden plik, zostanie
  uzyty tylko pierwszy) przyklad:

  `$normalize_files = [
      [
          'name' => 'test1.jpg',
          'type' => 'image/jpeg',
          'tmp_name' => '/tmp/nsl54Gs',
          'error' => 0,
          'size' => 1715,
      ],
      [
          'name' => 'test2.jpg',
          'type' => 'image/jpeg',
          'tmp_name' => '/tmp/1sl54GC',
          'error' => 0,
          'size' => 5368,
      ],
  ];
  `
- sciezkę do pliku

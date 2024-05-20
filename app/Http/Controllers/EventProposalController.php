<?php

namespace App\Http\Controllers;

use App\Models\EventProposal;
use Illuminate\Http\Request;

//test

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Response;



class EventProposalController extends Controller
{
  /* letak dekat controller event proposal */
  public function postEventProposal(Request $request)
  {
    $eventProposal = new EventProposal();

    $this->validate($request, [
      'purpose' => 'required|string|max:255',
      'background' => 'required|string|max:1000',
      'eventName' => 'required|string|max:255',
      'organizer' => 'required|string|max:255',
      'date' => 'required|string|max:255',
      'day' => 'required|string|max:255',
      'time' => 'required|string|max:255',
      'location' => 'required|string|max:255',
      'objective1' => 'required|string|max:255',
      'objective2' => 'required|string|max:255',
      'objective3' => 'required|string|max:255',

    ]);

    $eventProposal->purpose = $request['purpose'];
    $eventProposal->background = $request['background'];
    $eventProposal->eventName = $request['eventName'];
    $eventProposal->organizer = $request['organizer'];
    $eventProposal->date = $request['date'];
    $eventProposal->day = $request['day'];
    $eventProposal->time = $request['time'];
    $eventProposal->location = $request['location'];
    $eventProposal->objective1 = $request['objective1'];
    $eventProposal->objective2 = $request['objective2'];
    $eventProposal->objective3 = $request['objective3'];
    $eventProposal->save();

    return redirect()->route('submit-event-proposal-form');
  }

  public function postSecondEventProposal(Request $request)
  {
    /* $eventProposal = new EventProposal();

    $this->validate($request, [
      'purpose' => 'required|string|max:255',
      'background' => 'required|string|max:255',
    ]);

    $eventProposal->purpose = $request['purpose'];
    $eventProposal->background = $request['background'];
    $eventProposal->save();

    return redirect()->route('submit-event-proposal-form'); */
  }

  public function fetchData()
  {
    return EventProposal::latest()->first(); // Get the latest event proposal
  }


  /* public function generateWordDocument($data) */ //yang asal
  public function generateWordDocument($eventProposal) //yg modified
  {
    // Initialize PHPWord object
    $phpWord = new PhpWord();

    // Define styles for the different levels
    $phpWord->addNumberingStyle(
      'multilevel',
      array(
        'type' => 'multilevel',
        'levels' => array(
          array('pStyle' => 'Heading1', 'format' => 'decimal', 'text' => '%1.0', 'left' => 360, 'hanging' => 720, 'tabPos' => 360),
          array('pStyle' => 'Heading2', 'format' => 'decimal', 'text' => '%1.%2', 'left' => 1080, 'hanging' => 720, 'tabPos' => 360),
          array('pStyle' => 'Heading3', 'format' => 'lowerLetter', 'text' => '%3.', 'left' => 1800, 'hanging' => 720, 'tabPos' => 360),
        )
      )
    );

    /* $phpWord->addNumberingStyle(
      'multilevel',
      array(
        'type' => 'multilevel',
        'levels' => array(
          array(
            'pStyle' => 'Heading1',
            'format' => 'decimal',
            'text' => '%1.',
            'left' => 360,
            'hanging' => 720,
            'tabPos' => 360
          ),
          array(
            'pStyle' => 'Heading2',
            'format' => 'decimal',
            'text' => '%1.%2',
            'left' => 720,
            'hanging' => 720,
            'tabPos' => 720
          ),
          array(
            'pStyle' => 'Heading3',
            'format' => 'lowerLetter',
            'text' => '%3.',
            'left' => 1080,
            'hanging' => 720,
            'tabPos' => 1080
          ),
        )
      )
    ); */


    // Add a section
    $section = $phpWord->addSection();

    // Define paragraph style with indentation for level 2 items
    $phpWord->addParagraphStyle(
      'p2_style',
      array('indentation' => array('left' => 720))
    );

    $phpWord->addParagraphStyle(
      'p3_style',
      array('indentation' => array('left' => 360))
    );

    // Define text style for bold text
    $phpWord->addFontStyle(
      'boldText',
      array('bold' => true, 'name' => 'Arial', 'size' => 11)
    );

    // Define paragraph style for level 1 items' text
    $phpWord->addParagraphStyle(
      'level1_text_style',
      array('indentation' => array('left' => 720))
    );

    //Add textbreak
    $section->addTextBreak(4);


    //add Logo
    $logoPath = public_path('img/logo_uthm.png'); // Path to your logo image file
    $section->addImage($logoPath, [
      'width' => 268.08, // Width of the logo in points
      'height' => 76.32, // Height of the logo in points
      'alignment' => 'center', // Alignment of the logo within the page
    ]);

    /* $section->addText('UNIVERSITI TUN HUSSEIN ONN MALAYSIA', [
      'name' => 'Arial', // Font name
      'size' => 13, // Font size
      'bold' => true, // Bold style
      'alignment' => 'center', // Text alignment
      'valign' => 'center', // Vertical alignment
    ]); */

    $section->addText('UNIVERSITI TUN HUSSEIN ONN MALAYSIA', [
      'name' => 'Arial', // Font name
      'size' => 13, // Font size
      'bold' => true, // Bold style
    ], [
      'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Text alignment
    ]);

    $section->addTextBreak(1);

    $section->addText('KERTAS KERJA', [
      'name' => 'Arial', // Font name
      'size' => 13, // Font size
      'bold' => true, // Bold style
    ], [
      'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Text alignment
    ]);

    $section->addTextBreak(1);

    // Retrieve and add eventName below "KERTAS KERJA"
    $eventName = $eventProposal->eventName;
    $section->addText($eventName, [
      'name' => 'Arial', // Font name
      'size' => 12, // Font size
      'bold' => true, // Bold style
    ], [
      'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Text alignment
    ]);

    $section->addTextBreak(1);

    $section->addText('TEMPAT:', [
      'name' => 'Arial', // Font name
      'size' => 13, // Font size
      'bold' => true, // Bold style
    ], [
      'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Text alignment
    ]);

    $section->addTextBreak(1);

    $location = $eventProposal->location;
    $section->addText($location, [
      'name' => 'Arial', // Font name
      'size' => 12, // Font size
      'bold' => true, // Bold style
    ], [
      'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Text alignment
    ]);

    $section->addTextBreak(1);

    $section->addText('ANJURAN :', [
      'name' => 'Arial', // Font name
      'size' => 13, // Font size
      'bold' => true, // Bold style
    ], [
      'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Text alignment
    ]);

    $section->addTextBreak(1);

    $organizer = $eventProposal->organizer;
    $section->addText($organizer, [
      'name' => 'Arial', // Font name
      'size' => 12, // Font size
      'bold' => true, // Bold style
    ], [
      'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Text alignment
    ]);

    $section->addTextBreak(1);

    $section->addText('TARIKH :', [
      'name' => 'Arial', // Font name
      'size' => 13, // Font size
      'bold' => true, // Bold style
    ], [
      'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Text alignment
    ]);

    $date = $eventProposal->date;
    $section->addText($date, [
      'name' => 'Arial', // Font name
      'size' => 12, // Font size
      'bold' => true, // Bold style
    ], [
      'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Text alignment
    ]);

    //Page 2

    $section2 = $phpWord->addSection();

    // Add content to the second page
    /* $section2->addText('MAJLIS PERWAKILAN PELAJAR & PEJABAT HAL EHWAL PELAJAR', [
      'name' => 'Arial', // Font name
      'size' => 12, // Font size
      'bold' => true, // Bold style
    ], [
      'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Text alignment
    ]); */

    $secondPageText = 'MAJLIS PERWAKILAN PELAJAR & PEJABAT HAL EHWAL PELAJAR UNIVERSITI TUN HUSSEIN ONN MALAYSIA';
    $section2->addText(htmlspecialchars($secondPageText), [
      'name' => 'Arial', // Font name
      'size' => 12, // Font size
      'bold' => true, // Bold style
    ], [
      'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Text alignment
    ]);

    $section2->addTextBreak(1);

    $eventName = $eventProposal->eventName;
    $section2->addText($eventName, [
      'name' => 'Arial', // Font name
      'size' => 12, // Font size
      'bold' => true, // Bold style
    ], [
      'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, // Text alignment
    ]);

    // Define font style for bold headings and numbering
    $phpWord->addFontStyle(
      'boldText',
      array('bold' => true, 'name' => 'Arial', 'size' => 11)
    );

    $phpWord->addFontStyle(
      'notBoldText',
      array('bold' => false, 'name' => 'Arial', 'size' => 11, 'indentation' => ['left' => 360])
    );

    // Define a text style for font size 11
    $phpWord->addFontStyle(
      'size11', // Style name
      array('name' => 'Arial', 'size' => 11)
    );

    // Define text style for normal text with size 11
    $phpWord->addFontStyle(
      'normalText',
      array('name' => 'Arial', 'size' => 11)
    );

    // Add a title with numbering
    $section2->addListItem('TUJUAN', 0,/*  null */ 'boldText', 'multilevel');

    $phpWord->addParagraphStyle(
      'justifiedStyle',
      [
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
        'indentation' => ['left' => 360],
      ]
    );

    $phpWord->addParagraphStyle(
      'justifiedStyle2',
      [
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
        'indentation' => ['left' => 1080],
      ]
    );

    //
    $purpose = $eventProposal->purpose;
    $section2->addText($purpose, [
      'name' => 'Arial', // Font name
      'size' => 11, // Font size
      'bold' => false, // Bold style
    ], 'justifiedStyle');

    $section2->addTextBreak(1);

    $section2->addListItem('LATAR BELAKANG', 0, 'boldText', 'multilevel');

    $section2->addListItem('Pengenalan', 1, 'notBoldText', 'multilevel');

    $background = $eventProposal->background;
    $section2->addText($background, [
      'name' => 'Arial', // Font name
      'size' => 11, // Font size
      'bold' => false, // Bold style
    ], 'justifiedStyle2');

    $section2->addTextBreak(1);

    $section2->addListItem('NAMA AKTIVITI DAN PENGANJUR', 0, 'boldText', 'multilevel');
    $section2->addListItem('Nama Aktiviti', 1, 'notBoldText', 'multilevel');
    $section2->addText($eventName, [
      'name' => 'Arial', // Font name
      'size' => 11, // Font size
      'bold' => false, // Bold style
    ], 'justifiedStyle2');

    $section2->addListItem('Nama Penganjur', 1, 'notBoldText', 'multilevel');
    $section2->addText($organizer, [
      'name' => 'Arial', // Font name
      'size' => 11, // Font size
      'bold' => false, // Bold style
    ], 'justifiedStyle2');

    $section2->addTextBreak(1);

    $section2->addListItem('BUTIRAN AKTIVITI', 0, 'boldText', 'multilevel');

    $section2->addListItem('Tarikh', 1, 'notBoldText', 'multilevel');
    $section2->addText($date, [
      'name' => 'Arial', // Font name
      'size' => 11, // Font size
      'bold' => false, // Bold style
    ], 'justifiedStyle2');

    $section2->addListItem('Hari', 1, 'notBoldText', 'multilevel');
    $day = $eventProposal->day;
    $section2->addText($day, [
      'name' => 'Arial', // Font name
      'size' => 11, // Font size
      'bold' => false, // Bold style
    ], 'justifiedStyle2');

    $section2->addListItem('Lokasi', 1, 'notBoldText', 'multilevel');
    $section2->addText($location, [
      'name' => 'Arial', // Font name
      'size' => 11, // Font size
      'bold' => false, // Bold style
    ], 'justifiedStyle2');

    $section2->addListItem('Masa', 1, 'notBoldText', 'multilevel');
    $time = $eventProposal->time;
    $section2->addText($time, [
      'name' => 'Arial', // Font name
      'size' => 11, // Font size
      'bold' => false, // Bold style
    ], 'justifiedStyle2');


    $section2->addTextBreak(1);

    $objective1 = $eventProposal->objective1;
    $objective2 = $eventProposal->objective2;
    $objective3 = $eventProposal->objective3;

    $section2->addListItem('OBJEKTIF AKTIVITI', 0, 'boldText', 'multilevel');

    $section2->addListItem($objective1, 1, 'notBoldText', 'multilevel');
    $section2->addListItem($objective2, 1, 'notBoldText', 'multilevel');
    $section2->addListItem($objective3, 1, 'notBoldText', 'multilevel');

    $section2->addTextBreak(1);

    $section2->addListItem('PERNYATAAN MASALAH', 0, 'boldText', 'multilevel');

    $section2->addListItem('Nama dan Alamat Industri/Persatuan/Agensi/Badan Organisasi Luar', 1, 'notBoldText', 'multilevel');
    $section2->addListItem($background, 2, 'notBoldText', 'multilevel');
    /* $section2->addText('Objektif aktiviti adalah seperti berikut:', [
      'name' => 'Arial', // Font name
      'size' => 11, // Font size
      'bold' => false, // Bold style
    ], 'justifiedStyle');

    $section2->addText($background, [
      'name' => 'Arial', // Font name
      'size' => 11, // Font size
      'bold' => false, // Bold style
    ], 'justifiedStyle2'); */













    /*  $section2->addListItem('LATAR BELAKANG', 0, null, 'multilevel');
     $section2->addListItem('Pengenalan', 1, null, 'multilevel');
     $section2->addText('Malaysia adalah merupakan sebuah negara berdaulat dan merdeka, mengamalkan amalan raja berperlembagaan dan demokrasi berparlimen.', [
       'name' => 'Arial',
       'size' => 11,
       'bold' => true
     ], 'p3_style'); */






    // Add table
    /* $table = $section->addTable();

    // Add headers
    $table->addRow();
    $headers = ['purpose', 'background', 'eventName', 'organizer']; // Replace with your column names
    foreach ($headers as $header) {
      $table->addCell()->addText($header);
    }

    // Add data rows
    foreach ($data as $row) {
      $table->addRow();
      foreach ($row->toArray() as $column) {
        $table->addCell()->addText($column);
      }
    } */

    // Save the document
    $filename = 'exported_data.docx';
    $path = storage_path('app/' . $filename);
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save($path);

    return $path;
  }

  public function exportToWord()
  {
    // Fetch data
    $data = $this->fetchData();

    // Generate Word document
    $filePath = $this->generateWordDocument($data);

    // Download the document
    return Response::download($filePath);
  }
}

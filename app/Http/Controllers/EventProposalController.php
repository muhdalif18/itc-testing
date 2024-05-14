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
      'background' => 'required|string|max:255',
      'eventName' => 'required|string|max:255',
      'organizer' => 'required|string|max:255',
      'date' => 'required|string|max:255',
      'day' => 'required|string|max:255',
      'time' => 'required|string|max:255',
      'location' => 'required|string|max:255',

    ]);

    $eventProposal->purpose = $request['purpose'];
    $eventProposal->background = $request['background'];
    $eventProposal->eventName = $request['eventName'];
    $eventProposal->organizer = $request['organizer'];
    $eventProposal->date = $request['date'];
    $eventProposal->day = $request['day'];
    $eventProposal->time = $request['time'];
    $eventProposal->location = $request['location'];
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


  //test
  /* public function fetchData()
  {
    $data = EventProposal::all();

    return $data;
  } */

  public function fetchData()
  {
    return EventProposal::latest()->first(); // Get the latest event proposal
  }


  /* public function generateWordDocument($data) */ //yang asal
  public function generateWordDocument($eventProposal) //yg modified
  {
    // Initialize PHPWord object
    $phpWord = new PhpWord();

    // Add a section
    $section = $phpWord->addSection();

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

    /* $section->addText('KERTAS KERJA', [
      'name' => 'Arial', // Font name
      'size' => 13, // Font size
      'bold' => true, // Bold style
      'alignment' => 'center', // Text alignment
      'valign' => 'center', // Vertical alignment
    ]); */

    $section->addTextBreak(1);

    /* $eventName = $eventProposal->eventName;
    $section->addText($eventName, [
      'name' => 'Arial', // Font name
      'size' => 12, // Font size
      'bold' => true, // Bold style
      'alignment' => 'center', // Text alignment
      'valign' => 'center', // Vertical alignment
    ]); */

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

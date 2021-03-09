<!DOCTYPE html>
<html>
<head>
	<title>Tesaurus Bahasa Indonesia</title>
	<link rel="stylesheet" type="text/css" href="tesaurusbaru.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<div class="nav">
		<ul>
			<li><a href="tesaurusbaru.php"> Tesaurus.id </a></li>
			<li><a href="tentang.php"> Tentang </a></li>
			<li><a href="cara.php"> Cara Penggunaan</a></li>
		</ul>
	</div>
	<div class="artikel">
		<h1>TESAURUS.ID</h1>
		<form method="post" action="tesaurusbaru.php">
			<input id="searchbox" type="searchbox" name="cari" type="text" placeholder="Masukkan kata...">
			<input id="buttonsearch" type="submit" name="submit" value="Search"><br>

			<input id="checkbox" type="checkbox" name="stemming" value="KataDasar"> Kata Dasar 
			<input id="checkbox" type="checkbox" name="related" value="RelasiKata"> Relasi Kata 
		</form>
	</div>
	<div class="hasil">
	<?php
		require_once("vendor/autoload.php");
		require_once("sparqllib.php");

		if (isset($_POST['cari'])) {
			$kata = $_POST['cari'];

			print "<b> $kata , </b>";
			print "<br>";
			print "<br>";
			
			if (!empty($_POST['cari'])) {
				//1 STEMMING
				if (isset($_POST['stemming'])) {
					//MEMUAT LIBRARY SASTRAWI
					$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
					$stemmer  = $stemmerFactory->createStemmer();

					//MENJALANKAN STEMMING
					$output = $stemmer->stem($kata);

					//MENAMPILKAN HASIL STEMMING
					print "<button class=\"btn katadasar\"> KATA DASAR </button>";
					print " $output ";
					print "<br>";
					print "<br>";
				
				}

				//2 RELASI
				if (isset($_POST['related'])) {
					//KONEKSI DATABASE SKOSID
					$database = sparql_connect("https://app.alunalun.info/fuseki/skosid/query");
					if(!$database) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }

					//PREFIX DAN QUERY
					sparql_ns("skos", "http://www.w3.org/2004/02/skos/core#");

					$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s skos:related ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					//MENAMPILKAN HASIL RELASI
					print "<button class=\"btn relasikata\"> RELASI KATA </button>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "$row[$field]";
							print "  ";

						}
					}
					print "<br>";
					print "<br>";
	        	}

				//3 RAGAM BAHASA, KELAS KATA, KATA SERAPAN
				$database = sparql_connect("https://app.alunalun.info/fuseki/skosid/query");
				if(!$database) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }

				sparql_ns("skos", "http://www.w3.org/2004/02/skos/core#");
				sparql_ns("wordattr", "http://www.michimawan.koding.io/tesaurus/ontology/indonesianontology.owl#");


				print "<table>";
				print "<tr>";
				print "<th><button class=\"bttn kelaskata\"> KELAS KATA </button></th>";
				print "<th><button class=\"btn\"> ADJEKTIVA </button></th>";
				print "<th><button class=\"btn\"> ADVERBIA </button></th>";
				print "<th><button class=\"btn\"> NOMINA </button></th>";				
				print "<th><button class=\"btn\"> NUMERALIA </button></th>";
				print "<th><button class=\"btn\"> PARTIKEL </button></th>";
				print "<th><button class=\"btn\"> PRONOMINA </button></th>";
				print "<th><button class=\"btn\"> VERBA </button></th>";
				print "<br>";
	        	print "<br>";
	        	print "</tr>";

	        	print "<tr>";
	        	print "<td><button class=\"bttnn\"></button></td>";
	        	print "<td><button class=\"bttnn\">";
	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:adjektiva.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td>";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";
	        	

	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:adverbia.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "<br>";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";
	        	
	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:nomina.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";
	        	
	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:numeralia.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";
	        	
	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:partikel.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";
	        	
	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:pronomina.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";
	        	
	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:verba.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

				print "</tr>";	        		
	        	print "</table>";

	        	print "<table>";
				print "<tr>";
				print "<th><button class=\"bttn kataserapan\"> KATA SERAPAN </button></th>";
				print "<th><button class=\"btn\"> ARAB </button></th>";
				print "<th><button class=\"btn\"> BALI </button></th>";
				print "<th><button class=\"btn\"> BELANDA </button></th>";				
				print "<th><button class=\"btn\"> CINA </button></th>";
				print "<th><button class=\"btn\"> JAKARTA </button></th>";
				print "<th><button class=\"btn\"> JAWA </button></th>";
				print "<th><button class=\"btn\"> MINANGKABAU </button></th>";
				print "<th><button class=\"btn\"> SUNDA </button></th>";
				print "<th><button class=\"btn\"> SANSKERTA </button></th>";
				print "<br>";
	        	print "<br>";
	        	print "</tr>";

	        	print "<tr>";
	        	print "<td><button class=\"bttnn\"></button></td>";
	        	print "<td><button class=\"bttnn\">";
	        	
	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:Arab.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";

	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:Bali.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";

	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:Belanda.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";

	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:Cina.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";

	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:Jakarta.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";
	        	

	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:Jawa.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";
	        	

	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:Minangkabau.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";
	        	
	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:Sunda.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";
	        	
	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:Sanskerta.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "</tr>";	        		
	        	print "</table>";


	        	print "<table>";
				print "<tr>";
				print "<th><button class=\"bttn ragambahasa\"> RAGAM BAHASA </button></th>";
				print "<th><button class=\"btn\"> ARKAIS </button></th>";
				print "<th><button class=\"btn\"> CAKAPAN </button></th>";
				print "<th><button class=\"btn\"> HORMAT </button></th>";				
				print "<th><button class=\"btn\"> KIASAN </button></th>";
				print "<th><button class=\"btn\"> KLASIK </button></th>";
				print "<br>";
	        	print "<br>";
				print "</tr>";
				
	        	print "<tr>";
	        	print "<td><button class=\"bttnn\"></button></td>";
	        	print "<td><button class=\"bttnn\">";
	        	
	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:arkais.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";
	        	
	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:cakapan.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";
	        	
	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:hormat.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";
	        	
	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:kiasan.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";

	        	print "<td><button class=\"bttnn\">";
	        	

	        	$sparql = "SELECT ?HasilPencarian WHERE {
					?s skos:prefLabel \"$kata\"@id. 
					?s ?p wordattr:klasik.
					?s skos:altLabel ?HasilPencarian.} LIMIT 50";

					$result = sparql_query($sparql);
					if(!$result) { print sparql_errno() . ": " . sparql_error(). "\n";exit; }
					$fields = sparql_field_array($result);

					print "<table>";
					print "<tr>";
					while($row = sparql_fetch_array($result))
					{
						foreach($fields as $field)
						{
							print "<td style=\"text-align:center;\">";
							print "$row[$field]";
							print "</td>";
						}
						print "</tr>";
					}
					print "</table>";
	        	print "</button><br></td>";
				print "</tr>";	        		
	        	print "</table>";
			}
		}
	?>
	</div>
	<div class="footer">
		<p> Universitas Kristen Duta Wacana </p>
		<p> Fakultas Teknologi Informasi </p>
		<p> Prodi Informatika </p>
		<p> 2021 </p>
	</div>
</body>
</html>
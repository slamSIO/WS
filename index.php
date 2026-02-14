<?php
	require_once 'Modeles/ModeleLivreur.php' ;
	
	$modele = new ModeleLivreur() ;
	
	$methode = $_SERVER[ 'REQUEST_METHOD' ] ;
	$chemin = parse_url( $_SERVER[ 'REQUEST_URI' ] , PHP_URL_PATH ) ;
	

	if( $methode === 'GET' && preg_match( '#^/livreurs$#', $chemin ) ) {
		$livreurs = $modele -> getAll() ;
		http_response_code( 200 ) ;
		header( 'Content-Type: application/json' ) ;
		echo json_encode( $livreurs ) ;
		
	}
	elseif( $methode === 'GET' && preg_match( '#^/livreurs/(\d+)$#' , $chemin , $donnees ) ) {
		$livreur = $modele -> getOne( (int) $donnees[ 1 ] ) ;
		if( $livreur ){
			http_response_code( 200 ) ;
			header( 'Content-Type: application/json' ) ;
			echo json_encode( $livreur ) ;
		}
		else {
			http_response_code( 404 ) ;
			header( 'Content-Type: application/json' ) ;
		}
	}
	elseif( $methode === 'POST' && preg_match( '#^/livreurs$#', $chemin ) ) {
		$livreur = json_decode( file_get_contents( 'php://input' ) , true ) ;
		$id = $modele -> create( $livreur ) ;
		http_response_code( 201 ) ;
		header( 'Content-Type: application/json' ) ;
		header( "Location: /livreurs/$id" ) ;
	}
	elseif( $methode === 'PUT' && preg_match( '#^/livreurs/(\d+)$#' , $chemin , $donnees ) ) {
		$livreur = json_decode( file_get_contents( 'php://input' ) , true ) ;
		$nbLivreurs = $modele -> update( (int) $donnees[ 1 ] , $livreur ) ;
		
		if( $nbLivreurs == 1 ){
			http_response_code( 200 ) ;
			header( 'Content-Type: application/json' ) ;
		}
		elseif( $nbLivreurs == -1 ){
			http_response_code( 204 ) ;
			header( 'Content-Type: application/json' ) ;
		}
		else {
			http_response_code( 404 ) ;
			header( 'Content-Type: application/json' ) ;
		}
	}
	elseif( $methode === 'PATCH' && preg_match( '#^/livreurs/(\d+)$#' , $chemin , $donnees ) ) {
		$livreur = json_decode( file_get_contents( 'php://input' ) , true ) ;
		$nbLivreurs = $modele -> patch( (int) $donnees[ 1 ] , $livreur ) ;
			
		if( $nbLivreurs == 1 ){
			http_response_code( 200 ) ;
			header( 'Content-Type: application/json' ) ;
		}
		elseif( $nbLivreurs == -1 ){
			http_response_code( 204 ) ;
			header( 'Content-Type: application/json' ) ;
		}
		else {
			http_response_code( 404 ) ;
			header( 'Content-Type: application/json' ) ;
		}
	}
	elseif( $methode === 'DELETE' && preg_match( '#^/livreurs/(\d+)$#' , $chemin , $donnees ) ) {
		$nbLivreurs = $modele -> delete( (int) $donnees[ 1 ] ) ;
		
		if( $nbLivreurs == 1 ){
			http_response_code( 200 ) ;
			header( 'Content-Type: application/json' ) ;
		}
		else {
			http_response_code( 404 ) ;
			header( 'Content-Type: application/json' ) ;
		}
	}
	else {
		http_response_code( 404 );
		header( 'Content-Type: application/json' ) ;
	}
	
?>

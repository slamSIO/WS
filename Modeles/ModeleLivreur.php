<?php

	class ModeleLivreur {
		
		private $db ;

		public function __construct() {
			$this -> db = new SQLite3( 'BD/foody.db' ) ;
		}

		public function getAll() {
			$resultat = $this -> db -> query( "SELECT * FROM livreur" ) ;
			$livreurs = array() ;
			while ( $livreur = $resultat -> fetchArray( SQLITE3_ASSOC ) ) {
				$livreurs[] = $livreur ;
			}
			return $livreurs ;
		}

		public function getOne( $id ) {
			$stmt = $this -> db -> prepare( "SELECT * FROM livreur WHERE id = :id" ) ;
			$stmt->bindValue( ':id' , $id , SQLITE3_INTEGER ) ;
			$resultat = $stmt -> execute() ;
			$livreur = $resultat -> fetchArray( SQLITE3_ASSOC ) ;
			return $livreur ;
		}

		public function create( $livreur ) {
			
			if ( !isset( $livreur['nom'] , $livreur['prenom'] ) ) {
				return -1 ;
			}

			$stmt = $this -> db -> prepare( "INSERT INTO livreur (nom, prenom) VALUES (:nom, :prenom) " );
			$stmt -> bindValue( ':nom' , $livreur[ 'nom' ] , SQLITE3_TEXT ) ;
			$stmt -> bindValue( ':prenom' , $livreur[ 'prenom' ] , SQLITE3_TEXT ) ;
			$stmt -> execute() ;
			$id = $this -> db -> lastInsertRowID() ;
			return $id ;
		}

		public function update( $id , $livreur ) {
			
			$stmt = $this -> db -> prepare( "UPDATE livreur SET nom = :nom, prenom = :prenom WHERE id = :id" ) ;
			
			if( isset( $livreur[ 'nom' ] ) && isset( $livreur[ 'prenom' ] ) ) {
				$stmt -> bindValue( ':nom' , $livreur[ 'nom' ] , SQLITE3_TEXT ) ;
				$stmt -> bindValue( ':prenom' , $livreur[ 'prenom' ] , SQLITE3_TEXT ) ;
				$stmt -> bindValue( ':id' , $id , SQLITE3_INTEGER ) ;
				$stmt -> execute();
				$nbTuples = $this -> db -> changes() ; 
				return $nbTuples ;
			}
			return -1 ;
		}
		
		public function patch( $id , $livreur ) {
			
			$colonnes = array() ;
			if( isset( $livreur[ 'nom' ] ) ) {
				$colonnes[] = "nom = :nom";
			}
			if( isset( $livreur[ 'prenom' ] ) ) {
				$colonnes[] = "prenom = :prenom";
			}

			if( empty( $colonnes ) ) {
				
				return -1 ;
			}

			$sql = "UPDATE livreur SET " . implode(', ', $colonnes) . " WHERE id = :id" ;
			$stmt = $this -> db -> prepare( $sql ) ;

			if( isset( $livreur[ 'nom' ] ) ) {
				$stmt -> bindValue( ':nom' , $livreur[ 'nom' ] , SQLITE3_TEXT ) ;
			}
			if( isset( $livreur[ 'prenom' ] ) ) {
				$stmt -> bindValue( ':prenom' , $livreur[ 'prenom' ] , SQLITE3_TEXT ) ;
			}

			$stmt->bindValue( ':id' , $id , SQLITE3_INTEGER ) ;
			$stmt->execute();

			$nbTuples = $this -> db -> changes() ; 
			return $nbTuples ;
		}

		public function delete( $id ) {
			// Votre code ici
			return 0 ;
		}

	}
	
?>

const fs = require( 'fs-extra' );
const path = require( 'path' );
const archiver = require( 'archiver' );

const sourceDir = path.resolve( __dirname, '../build' );
const zipFilePath = path.resolve( __dirname, '../pageflash.zip' );

// Check if the "pageflash.zip" file exists
if ( fs.existsSync( zipFilePath ) ) {
	// Delete the "pageflash.zip" file
	fs.unlinkSync( zipFilePath );
	console.log( 'Removed existing "pageflash.zip" file.' );
}

const archive = archiver( 'zip', { zlib: { level: 9 } } );
const output = fs.createWriteStream( zipFilePath );

output.on( 'close', () => {
	console.log( 'ZIP archive created successfully.' );
} );

archive.on( 'error', ( err ) => {
	throw err;
} );

archive.pipe( output );

// Check if the source directory exists
if ( fs.existsSync( sourceDir ) ) {
	// Add the contents of the "pageflash" directory to the ZIP archive
	archive.directory( sourceDir, false );

	// Finalize the archive
	archive.finalize();
} else {
	console.error( `Source directory "${ sourceDir }" not found.` );
}

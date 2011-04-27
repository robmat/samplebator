//
//  GPMap.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 11/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "GPMap.h"
#import "UIKit/UIKit.h"
#import "ViewEmergenciesMap.h"
#import "MyAnnotation.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import <AVFoundation/AVAudioPlayer.h>
#import "GPMapDetailsView.h"
#import "JSON.h"

@implementation GPMap

@synthesize searchBar ;

-(NSString *) dataFilePathGP{
	
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString * documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:kFilenameGP ] ;
}
/*
 // The designated initializer.  Override if you create the controller programmatically and want to perform customization that is not appropriate for viewDidLoad.
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    if (self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil]) {
        // Custom initialization
    }
    return self;
}
*/

//________Show_me_actual_position__________________________________________________________________________________________________________________________________________



-(IBAction)WhereAmIButton:(id)sender{
	
	mapa.showsUserLocation = TRUE ;
	MKCoordinateRegion region = { {0.0,0.0}, {0.0,0.0} };
	region.center.latitude = mapa.userLocation.coordinate.latitude ;
	region.center.longitude = mapa.userLocation.coordinate.longitude ;
	NSString * latitudeLABEL = [[NSString alloc] initWithFormat:@"Latitude: %f", mapa.userLocation.coordinate.latitude ] ;
	NSString * longitudeLABEL = [[NSString alloc] initWithFormat:@"Longitude: %f", mapa.userLocation.coordinate.longitude ] ;
	//NSLog(@"%@", latitudeLABEL) ;
	//NSLog(@"%@", longitudeLABEL) ;	
	region.span.longitudeDelta = 0.0175 ;
	region.span.latitudeDelta = 0.0175 ;
	[mapa setRegion:region animated:YES];
	
}

//_______________________________________________________________________________________________________________________________________

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
		
    [super viewDidLoad];
	
	avanzar = FALSE ;
	
	//CUIDADO PORQUE ESTOY CREANDO UNA SIMULACION!!!!!!!!!! Me centro en Bristol, pero tengo que centrarme en la posicion del usuario!!!!
	//Acerco la view a la posicion del usuario:
		mapa.showsUserLocation = TRUE ;

		MKCoordinateRegion region;
		region.center.latitude  = 51.455313;
		region.center.longitude = -2.591902;
	
		//Set Zoom level using Span
		MKCoordinateSpan span;
		//span.latitudeDelta  = .005;
		//span.longitudeDelta = .005;
		span.latitudeDelta  = 0.175;
		span.longitudeDelta = 0.175;
	
		region.span = span;
	
		//Move the map and zoom
		[mapa setRegion:region animated:YES];
	
	[mapa release] ;
	
	self.title = @"GP" ;
	
	mapa.showsUserLocation = TRUE ;
	
	self.searchBar.delegate = self;
	
	self.searchBar.showsCancelButton = YES;
	[self.view addSubview: self.searchBar];
	
	
	searchBar.barStyle = UIBarStyleBlackOpaque ;
	
	
	// Load information from array 
	
	gpLocations = [[NSArray alloc] initWithContentsOfFile:[[NSBundle mainBundle] 
																	pathForResource:@"gpLeo" 
																	ofType:@"plist"]];
	
	
	for (NSDictionary *gpDict in gpLocations){
		MyAnnotation *annotation = [[MyAnnotation alloc] initWithDictionary: gpDict];
		
		[mapa addAnnotation:annotation];
		
		[annotation release];
	}
	
	UIBarButtonItem * homeButton = [[[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"Home", @"")	style: UIBarButtonItemStyleBordered target:self action:@selector(goHome:)] autorelease ] ;
	self.navigationItem.rightBarButtonItem = homeButton ;
	
	//viewDetail.alpha = 0.0 ;
	viewDetail.hidden = TRUE ;
	
	//UIBarButtonItem * backBar = [[UIBarButtonItem alloc] initWithTitle:@"Backaaaa" style: UIBarButtonItemStyleDone target: nil action: nil ] ;
	//self.navigationController.navigationItem.backBarButtonItem = backBar ;
	
	//self.navigationController.navigationItem.backBarButtonItem.title = @"Back" ;
	

	//Centro la aplicacion en la posicion del usuario:
	
	//Showing the user positiion!!
	MKCoordinateRegion region2 = { {0.0,0.0}, {0.0,0.0} };
	region2.center.latitude = mapa.userLocation.coordinate.latitude ;
	region2.center.longitude = mapa.userLocation.coordinate.longitude ;
	NSString * latitudeLABEL = [[NSString alloc] initWithFormat:@"Latitude: %f", mapa.userLocation.coordinate.latitude ] ;
	//NSString * longitudeLABEL = [[NSString alloc] initWithFormat:@"Longitude: %f", mapa.userLocation.coordinate.longitude ] ;
	
	float latitudeFLOAT = mapa.userLocation.coordinate.latitude ;
	float longitudeFLOAT  = mapa.userLocation.coordinate.longitude ;
	
	if( latitudeFLOAT == -180.000000 ){
		//NSLog(@"detectadoooooo!! float" ) ;
		//Como no ha cogido las coordenadas correctamente, muestro la posicion en Bristol:
		region2.center.latitude  = 51.455313;
		region2.center.longitude = -2.591902;

		//No ha sido posible tomar tus coordenadas GPS.
		UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"NHS Bristol" message: @"We didn't found your GPS position. Please check your connection." delegate:self cancelButtonTitle:@"OK" otherButtonTitles: nil];
		
		[alert show];
		[alert release];
		
	}

	//NSLog(@"%f", latitudeFLOAT) ;
	region2.span.longitudeDelta = 0.0575 ;
	region2.span.latitudeDelta = 0.0575 ;


	//If im opening the app outside of Bristol, im going to show a message:
	
	minLatitude = 51.25 ;
	maxLatitude = 51.65 ;
	minLongitude = -2.99 ;
	maxLongitude = -2.30 ;

	BOOL PosicionActualDentroBristol = TRUE ;

	//Si estoy fuera de Bristol tengo que avisarlo:
	if( (latitudeFLOAT > maxLatitude)){
		PosicionActualDentroBristol = FALSE ;
	}
	if (latitudeFLOAT < minLatitude) {
		PosicionActualDentroBristol = FALSE ;			
	}
	if (longitudeFLOAT > maxLongitude) {
		PosicionActualDentroBristol = FALSE ;
	}
	if (longitudeFLOAT < minLongitude) {
		PosicionActualDentroBristol = FALSE ;			
	}
	
	if (PosicionActualDentroBristol) {
		[mapa setRegion:region2 animated:YES];
	}
	else {
		//Como no estoy dentro de Bristol muestro el mapa en Bristol
		UIAlertView * alert =	[[UIAlertView alloc] initWithTitle:@"NHS Bristol:" message:@"You are outsite of Bristol. Please visit the NHS website by clicking below." delegate:self cancelButtonTitle:@"Cancel" otherButtonTitles:@"NHS Website", nil];
		[alert show];
		[alert release];
		
		region2.center.latitude  = 51.455313;
		region2.center.longitude = -2.591902;
		[mapa setRegion:region2 animated:YES];
	}
}

//_______________________viewForAnnotation________________________________________________________________________________________________________________



- (MKAnnotationView *)mapView:(MKMapView *)mapView viewForAnnotation:(id <MKAnnotation>)annotation{
	
	
	//NSLog(@"Entro en annotationView ViewForAnnotation*****") ;
	
	if (mapa.userLocation == annotation){
		//NSLog(@"Entro en annotationView ViewForAnnotation       NIL       *****") ;
		return nil;
	}
	
	NSString *identifier = @"MY_IDENTIFIER";
	
	MKAnnotationView *annotationView = [mapa dequeueReusableAnnotationViewWithIdentifier:identifier];
	
	if (annotationView == nil){
		annotationView = [[[MKAnnotationView alloc] initWithAnnotation:annotation 
													   reuseIdentifier:identifier] 
						  autorelease];
		annotationView.image = [UIImage imageNamed:@"gp menu.png"];
		
		
		annotationView.canShowCallout = YES;
		
		annotationView.rightCalloutAccessoryView = [UIButton buttonWithType:UIButtonTypeDetailDisclosure];
		annotationView.leftCalloutAccessoryView =  [[[UIImageView  alloc] initWithImage:[UIImage imageNamed:@"whitelogo.png"]] autorelease];
		
	}
	
	return annotationView;
}

//_______________________viewForAnnotation::Accesory_Tapped________________________________________________________________________________________________________________

- (void)mapView:(MKMapView *)mapView annotationView:(MKAnnotationView *)view calloutAccessoryControlTapped:(UIControl *)control
{
	avanzar = TRUE ;
	NSString * tempoName = [NSString alloc];

	//Step1: Tengo el nombre, voy a buscar el indice en el array para extraer el resto de los datos.
	
		NSString * name = [NSString alloc ];
		name = [view.annotation subtitle] ;
		int numElementos = [gpLocations count] ; 
	
		NSDictionary * dict = [[NSDictionary alloc] init] ; 
	
		BOOL encontrado = FALSE ;
		int i = 0 ;
		int indiceFINAL = 0 ;
	
		NSLog(@"Num elementos: %i", numElementos) ;
	
		while ( (i < numElementos) && (!encontrado) ) {
			dict = [gpLocations objectAtIndex:i] ;
			tempoName = [ dict objectForKey:@"postcode"] ;
		
			if ([tempoName isEqualToString: name ]) {
				encontrado = TRUE;
				indiceFINAL = i ;
			}
			i++ ;
		}
		NSLog(@"Indice final: %i", indiceFINAL) ;
	

	//Step2: Con el indice, voy a extraer el resto de los datos.

		NSMutableArray * array = [[NSMutableArray alloc] initWithObjects: @"0",@"1", @"2",@"3",@"4",@"5",@"6",@"7",@"8", nil ] ;
		NSString * namefinal = [NSString alloc] ;
		NSString * namePartner = [NSString alloc] ;	
		NSString * managerName = [NSString alloc] ;
		NSString * managerMail = [NSString alloc] ;
		NSString * addressA = [NSString alloc] ;
		NSString * addressB = [NSString alloc] ;
		NSString * addressC = [NSString alloc] ;
		NSString * addressD = [NSString alloc] ;
		NSString * phone = [NSString alloc] ;
		NSString * postcode = [NSString alloc] ;
		//NSString * mail = [NSString alloc] ;
	
		namefinal    = [dict objectForKey:@"name" ]       ;
		managerName  = [dict objectForKey:@"manager"]	 ;	
		namePartner  = [dict objectForKey:@"partner"]	 ;						   
		addressA     = [dict objectForKey:@"address"]     ;
		addressB     = [dict objectForKey:@"address4"]     ;
		addressC     = [dict objectForKey:@"postcode"]     ;
		addressD     = [dict objectForKey:@"address5"]     ;

		phone        = [dict objectForKey:@"phone"]       ;
		managerMail	 = [dict objectForKey:@"managerMail"] ;
		postcode	 = [dict objectForKey:@"postcode"]    ;
		
		//NSLog(@"Name final: %@", namefinal) ;
	
	//Step3: Almaceno el resto de los datos, para darselos al siguiente view.
	
		[array replaceObjectAtIndex:0 withObject: (@"%@",namefinal) ];
		[array replaceObjectAtIndex:1 withObject: (@"%@",namePartner)   ] ;
		[array replaceObjectAtIndex:2 withObject: (@"%@",managerName)     ] ;
		[array replaceObjectAtIndex:3 withObject: (@"%@",managerMail )     ] ;
		[array replaceObjectAtIndex:4 withObject: (@"%@",addressA ) ] ;
		[array replaceObjectAtIndex:5 withObject: (@"%@",addressB ) ] ;
		[array replaceObjectAtIndex:6 withObject: (@"%@",addressC ) ] ;
		[array replaceObjectAtIndex:7 withObject: (@"%@",addressD ) ] ;
		[array replaceObjectAtIndex:8 withObject: (@"%@",phone ) ] ;	
	
		[array writeToFile:[ self dataFilePathGP ] atomically:YES ] ;
		[array release] ;
		//NSLog(@"Datos almacenados correctamente para el nuevo view") ;	
	
	//Step4: Llamo al siguiente view a traves de un push view controller.
	
		GPMapDetailsView * varGPMapDetailsView = [[GPMapDetailsView alloc] initWithNibName:@"GPMapDetailsView" bundle:nil ] ;
		[[self navigationController] pushViewController:varGPMapDetailsView animated:YES];
		[varGPMapDetailsView release] ;
	
}

//__________________________________________________________________________________________________________________________________________________


//_______________ENVIA Y RECIBE LA BUSQUEDA CON GOOGLE MAPS___________________________________________________________________________________________________________________________________


- (void) searchCoordinatesForAddress:(NSString *)inAddress
{
  

	//Build the string to Query Google Maps.
    NSMutableString *urlString = [NSMutableString stringWithFormat:@"http://maps.google.com/maps/geo?q=%@ bristol UK?output=json",inAddress];
	
    //Replace Spaces with a '+' character.
    [urlString setString:[urlString stringByReplacingOccurrencesOfString:@" " withString:@"+"]];
	
    //Create NSURL string from a formate URL string.
    NSURL *url = [NSURL URLWithString:urlString];
	
    //Setup and start an async download.
    //Note that we should test for reachability!.
    NSURLRequest *request = [[NSURLRequest alloc] initWithURL:url];
    NSURLConnection *connection = [[NSURLConnection alloc] initWithRequest:request delegate:self];
	
    [connection release];
    [request release];
	 
	 
}

//__________________________________________________________________________________________________________________________________________________

#pragma mark -
#pragma mark UISearchBarDelegate


// called when keyboard SEARCH button pressed
- (void)searchBarSearchButtonClicked:(UISearchBar *)searchBar{

	
	//NSLog(@"%@", self.searchBar.text) ;
	//NSString * tempo = [[NSString alloc] initWithString:(@"%@ %@", self.searchBar.text, @"UK") ] ;
	
	
	[self searchCoordinatesForAddress:[self.searchBar text]];
	
	
	//orig: [self searchCoordinatesForAddress:[self.searchBar text]];
	
	
	
    //Hide the keyboard.
    [self.searchBar resignFirstResponder];
	
}



// called when CANCEL button pressed
- (void)searchBarCancelButtonClicked:(UISearchBar *)searchBar{
	//[self.searchBar resignFirstResponder];
}





- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data {   
	
	
    //The string received from google's servers
    NSString *jsonString = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
	
    //JSON Framework magic to obtain a dictionary from the jsonString.
    NSDictionary *results = [jsonString JSONValue];
	
    //Now we need to obtain our coordinates
    NSArray *placemark  = [results objectForKey:@"Placemark"];
    NSArray *coordinates = [[placemark objectAtIndex:0] valueForKeyPath:@"Point.coordinates"];
	
    //I put my coordinates in my array.
    double longitude = [[coordinates objectAtIndex:0] doubleValue];
    double latitude = [[coordinates objectAtIndex:1] doubleValue];
    //Debug.
    
	//NSLog(@"MUUUUY IMPORTANTE         Latitude - Longitude: %f %f", latitude, longitude);
	
	
	
	
	//Center in the search area ****
	
	MKCoordinateRegion region;
	
	MKCoordinateSpan span;
	
	
    region.center.latitude  = latitude;
    region.center.longitude = longitude;
	
    //Set Zoom level using Span
    //MKCoordinateSpan span;
    //span.latitudeDelta  = .005;
    //span.longitudeDelta = .005;
    span.latitudeDelta  = 0.0175;
    span.longitudeDelta = 0.0175;
	
    region.span = span;
	
    //Move the map and zoom
	
	minLatitude = 51.25 ;
	maxLatitude = 51.65 ;
	
	minLongitude = -2.99 ;
	maxLongitude = -2.30 ;
	
	BOOL BusquedaDentroBristol = TRUE ;
	
	if ((latitude != 0)&&(longitude != 0)) {  // Si la busqueda ha sido un exito...
	    BusquedaDentroBristol = TRUE ;

		//Si estoy fuera de Bristol tengo que avisarlo:
		if( (latitude > maxLatitude)){
			BusquedaDentroBristol = FALSE ;
		}
		if (latitude < minLatitude) {
			BusquedaDentroBristol = FALSE ;			
		}
		if (longitude > maxLongitude) {
			BusquedaDentroBristol = FALSE ;
		}
		if (longitude < minLongitude) {
			BusquedaDentroBristol = FALSE ;			
		}
		
		if (BusquedaDentroBristol) {
			[mapa setRegion:region animated:YES];
		}
		else {
			UIAlertView * alert =	[[UIAlertView alloc] initWithTitle:@"NHS Bristol:" message:@"Sorry but this application does not cover the area that you have searched. Please visit the NHS website by clicking below." delegate:self cancelButtonTitle:@"Cancel" otherButtonTitles:@"NHS Website", nil];
			[alert show];
			[alert release];
		}

	}
	else {
		UIAlertView * alert =	[[UIAlertView alloc] initWithTitle:@"NHS Bristol:" message:@"Sorry but this application does not cover the area that you have searched. Please visit the NHS website by clicking below." delegate:self cancelButtonTitle:@"Cancel" otherButtonTitles:@"NHS Website", nil];
		[alert show];
		[alert release];
	}
	
	
	
    [jsonString release];
	 
	 
}


- (void) zoomMapAndCenterAtLatitude:(double) latitude andLongitude:(double) longitude{
	
	
    MKCoordinateRegion region;
    region.center.latitude  = latitude;
    region.center.longitude = longitude;
	
    //Set Zoom level using Span
    MKCoordinateSpan span;
    //span.latitudeDelta  = .005;
    //span.longitudeDelta = .005;
    span.latitudeDelta  = 0.175;
    span.longitudeDelta = 0.175;
	
    region.span = span;
	
    //Move the map and zoom
    [mapa setRegion:region animated:YES];
	 
	 
}



//_______________CONFIGURE THE ALERTS BUTTONS___________________________________________________________________________________________________________________________________



-(void)alertView:(UIAlertView *) alertView clickedButtonAtIndex: (NSInteger)buttonIndex {
	
	
	if (buttonIndex == 0){
		//Cancel Button			
		
	}
	if (buttonIndex == 1){
		//ContinueButton, GotoWebSite button
		
		//Le digo al programa, que entramos en el estado 51, significa que quiero navegar hasta el browser NHS
		estado_anterior = 51 ;
		estado_siguiente = 51 ;
		
		[[self navigationController] popToRootViewControllerAnimated:NO ] ;
	}
	
}	


-(IBAction)goHome:(id)sender{
	[[self navigationController] popToRootViewControllerAnimated:NO ] ;
}


-(void)viewWillAppear:(BOOL)animated{
	self.title = @"General Practice" ;	
	estado_siguiente = -1 ;
	estado_anterior  = 3 ; //Voy a volver desde el mapa
	avanzar = FALSE ;
}

-(void)viewWillDisappear:(BOOL)animated{
	if (avanzar == TRUE) {
		self.title = @"Back" ;
	}	
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
}

// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}


- (void)didReceiveMemoryWarning {
	// Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
	
	// Release any cached data, images, etc that aren't in use.
}

- (void)viewDidUnload {
	// Release any retained subviews of the main view.
	// e.g. self.myOutlet = nil;
}


- (void)dealloc {
    [super dealloc];
}


@end

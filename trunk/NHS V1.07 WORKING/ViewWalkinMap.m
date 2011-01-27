

#import "ViewWalkinMap.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import<AVFoundation/AVAudioPlayer.h>
#import "UIKit/UIKit.h"
#import "MyAnnotation.h"
#import "JSON.h"
#import "UIKit/UIKit.h"
#import "viewWalkinMapDetails.h"
#import "GeoLocation.h"
#import "InWhichCountyAmI.h"
#import "DataHandler.h"

@implementation ViewWalkinMap

@synthesize searchBar ;

-(NSString *) dataFilePathWalkin{
	
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString * documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:kFilenameWalkin ] ;
	
}



//_______________________________________________________________________________________________________________________________________

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	
    [super viewDidLoad];
	
	avanzar = FALSE ;
	
	mapa.showsUserLocation = YES;
	
	float latitudeFLOAT = mapa.userLocation.coordinate.latitude ;
    float longitudeFLOAT  = mapa.userLocation.coordinate.longitude ;
	
	//latitudeFLOAT = 53.713570;
	//longitudeFLOAT = -0.340825;
	
	CLLocation* location = [[CLLocation alloc] initWithLatitude: latitudeFLOAT longitude:longitudeFLOAT];
	
	InWhichCountyAmI* iwcai = [[InWhichCountyAmI alloc] init];
	NSString* countyId = [iwcai giveCountyWithLongtitude:location.coordinate.longitude latitude:location.coordinate.latitude];
	BOOL insideAnyCounty = (countyId != nil);
	
	countyId = countyId == nil ? @"yorkshire_coord" : countyId;
	//CLLocation* countyCenter = [iwcai giveCountyCenterWithCountyId:countyId];
	
	//CUIDADO PORQUE ESTOY CREANDO UNA SIMULACION!!!!!!!!!! Me centro en Bristol, pero tengo que centrarme en la posicion del usuario!!!!
	//Acerco la view a la posicion del usuario:
	mapa.showsUserLocation = YES ;
	
	MKCoordinateRegion region;
	region.center.latitude  = location.coordinate.latitude;
	region.center.longitude = location.coordinate.longitude;
	
	//Set Zoom level using Span
	MKCoordinateSpan span;
	//span.latitudeDelta  = .005;
	//span.longitudeDelta = .005;
	span.latitudeDelta  = 0.009;
	span.longitudeDelta = 0.009;
	
	region.span = span;
	
	//Move the map and zoom
	[mapa setRegion:region animated:YES];
	
	[mapa release] ;
	
	
	self.title = @"Walk-in Centre" ;
	
	mapa.showsUserLocation = YES ;
	
	self.searchBar.delegate = self;
	
	self.searchBar.showsCancelButton = YES;
	[self.view addSubview: self.searchBar];
	
	searchBar.barStyle = UIBarStyleBlackOpaque ;
	
	// Load information from array 
	
	DataHandler* dh = [[DataHandler alloc] init];
	walkinLocations = [dh getDataByCategory:@"walkincentre"];
	[walkinLocations retain];
	
	for (NSDictionary * walkinDict in walkinLocations){
		MyAnnotation * annotation = [[MyAnnotation alloc] initWithDictionary: walkinDict];
		[mapa addAnnotation:annotation];
		[annotation release];
	}
	
	UIBarButtonItem * homeButton = [[[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"Home", @"")	style: UIBarButtonItemStyleBordered target:self action:@selector(goHome:)] autorelease ] ;
	self.navigationItem.rightBarButtonItem = homeButton ;
	
	//viewDetail.alpha = 0.0 ;
	viewDetail.hidden = TRUE ;
	
	//Showing the user positiion!!
	MKCoordinateRegion region2 = { {0.0,0.0}, {0.0,0.0} };
	//region2.center.latitude = mapa.userLocation.coordinate.latitude ;
	//region2.center.longitude = mapa.userLocation.coordinate.longitude ;
	//NSString * latitudeLABEL = [[NSString alloc] initWithFormat:@"Latitude: %f", mapa.userLocation.coordinate.latitude ] ;
	//NSString * longitudeLABEL = [[NSString alloc] initWithFormat:@"Longitude: %f", mapa.userLocation.coordinate.longitude ] ;
	
	//float latitudeFLOAT = mapa.userLocation.coordinate.latitude ;
	//float longitudeFLOAT  = mapa.userLocation.coordinate.longitude ;
	
	if( location.coordinate.latitude == -180.000000 ){
		//NSLog(@"detectadoooooo!! float" ) ;
		//Didn't find the user location, default to Yorkshire.
		CLLocation* yorkshireCenter = [iwcai giveCountyCenterWithCountyId:@"yorkshire_coord"];
		region2.center.latitude  = yorkshireCenter.coordinate.latitude;
		region2.center.longitude = yorkshireCenter.coordinate.longitude;
		
		//No ha sido posible tomar tus coordenadas GPS.
		UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"NHS:" message: @"We didn't found your GPS position. Please check your connection." delegate:self cancelButtonTitle:@"OK" otherButtonTitles: nil];
		
		[alert show];
		[alert release];
		
	}
	
	region2.span.longitudeDelta = 0.009 ;
	region2.span.latitudeDelta = 0.009 ;
	
	
	//If im opening the app outside of Bristol, im going to show a message:
	
	if (!insideAnyCounty) {
		//[mapa setRegion:region2 animated:YES];
		
		//Como no estoy dentro de Bristol muestro el mapa en Bristol
		UIAlertView * alert =	[[UIAlertView alloc] initWithTitle:@"NHS:" message:@"You are outsite of supported counties. Please visit the NHS website by clicking below." delegate:self cancelButtonTitle:@"Cancel" otherButtonTitles:@"NHS Website", nil];
		[alert show];
		[alert release];
		
		//region2.center.latitude  = 51.455313;
		//region2.center.longitude = -2.591902;
		//[mapa setRegion:region2 animated:YES];
	}
	[iwcai release];	
	
}

//_______________________viewForAnnotation________________________________________________________________________________________________________________



- (MKAnnotationView *)mapView:(MKMapView *)mapView viewForAnnotation:(id <MKAnnotation>)annotation{
	
	
	//NSLog(@"Entro en annotationView ViewForAnnotation*****") ;
	
	if (mapa.userLocation == annotation){
		return nil;
	}
	
	NSString *identifier = @"MY_IDENTIFIER";
	
	MKAnnotationView *annotationView = [mapa dequeueReusableAnnotationViewWithIdentifier:identifier];
	
	if (annotationView == nil){
		annotationView = [[[MKAnnotationView alloc] initWithAnnotation:annotation 
													   reuseIdentifier:identifier] 
						  autorelease];
		annotationView.image = [UIImage imageNamed:@"walkincentre menu.png"];
		
		
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
	int numElementos = [walkinLocations count] ; 
	
	NSDictionary * dict = [[NSDictionary alloc] init]  ; 
	
	BOOL encontrado = FALSE ;
	int i = 0 ;
	int indiceFINAL = 0 ;
	
	NSLog(@"Num elementos: %i", numElementos) ;
	
	while ( (i < numElementos) && (!encontrado) ) {
		dict = [walkinLocations objectAtIndex:i] ;
		tempoName = [ dict objectForKey:@"postcode"] ;
		
		if ([tempoName isEqualToString: name ]) {
			encontrado = TRUE;
			indiceFINAL = i ;
		}
		i++ ;
	}
	NSLog(@"Indice final: %i", indiceFINAL) ;
	
	
	//Step2: Con el indice, voy a extraer el resto de los datos.
	
	NSMutableArray * array = [[NSMutableArray alloc] initWithObjects: @"0",@"1", @"2",@"3",@"4",@"5",@"6",@"7",@"8", @"9", nil ] ;
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
//	NSString * mail = [NSString alloc] ;
	
	namefinal    = [dict objectForKey:@"name" ]       ;
	managerName  = [dict objectForKey:@"name" ]       ;
	namePartner  = [dict objectForKey:@"name" ]       ;
	addressA     = [dict objectForKey:@"addressLarge"]     ;
	addressB     = [dict objectForKey:@"district"]     ;
	addressC     = [dict objectForKey:@"postcode"]     ;
	addressD     = [dict objectForKey:@"city"]     ;
	phone        = [dict objectForKey:@"telephone"]       ;
	managerMail	 = [dict objectForKey:@"name" ]       ;
	postcode	 = [dict objectForKey:@"postcode"]    ;
	
	NSLog(@"Name final: %@", namefinal) ;
	
	
	//Step3: Almaceno el resto de los datos, para darselos al siguiente view.
	
	[array replaceObjectAtIndex:0 withObject: (@"%@",namefinal) ];
	
	[array replaceObjectAtIndex:1 withObject: (@"%@",namePartner) ] ;
	[array replaceObjectAtIndex:2 withObject: (@"%@",managerName) ] ;
	[array replaceObjectAtIndex:3 withObject: (@"%@",managerMail) ] ;
	[array replaceObjectAtIndex:4 withObject: (@"%@",addressA ) ] ;
	[array replaceObjectAtIndex:5 withObject: (@"%@",addressB ) ] ;
	[array replaceObjectAtIndex:6 withObject: (@"%@",addressB ) ] ;
	[array replaceObjectAtIndex:7 withObject: (@"%@",addressC ) ] ;
	[array replaceObjectAtIndex:8 withObject: (@"%@",addressD ) ] ;	
	[array replaceObjectAtIndex:9 withObject: (@"%@",phone ) ] ;		
	
	[array writeToFile:[ self dataFilePathWalkin ] atomically:YES ] ;
	[array release] ;
	NSLog(@"Datos almacenados correctamente para el nuevo view") ;	
	
	//Step4: Llamo al siguiente view a traves de un push view controller.
	
	viewWalkinMapDetails * varviewWalkinMapDetails = [[viewWalkinMapDetails alloc] initWithNibName:@"viewWalkinMapDetails" bundle:nil ] ;
	[[self navigationController] pushViewController:varviewWalkinMapDetails animated:YES];
	[varviewWalkinMapDetails release] ;
	
	
	
}

//__________________________________________________________________________________________________________________________________________________


//_______________ENVIA Y RECIBE LA BUSQUEDA CON GOOGLE MAPS___________________________________________________________________________________________________________________________________


- (void) searchCoordinatesForAddress:(NSString *)inAddress
{
	
	//Build the string to Query Google Maps.
    NSMutableString *urlString = [NSMutableString stringWithFormat:@"http://maps.google.com/maps/geo?q=%@ ?output=json",inAddress];
	
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
    
	NSLog(@"MUUUUY IMPORTANTE         Latitude - Longitude: %f %f", latitude, longitude);
	
	
	
	
	//Center in the search area ****
	
	MKCoordinateRegion region;
	
	MKCoordinateSpan span;
	
	
    region.center.latitude  = latitude;
    region.center.longitude = longitude;
	
    //Set Zoom level using Span
    //MKCoordinateSpan span;
    //span.latitudeDelta  = .005;
    //span.longitudeDelta = .005;
    span.latitudeDelta  = 0.009;
    span.longitudeDelta = 0.009;
	
    region.span = span;
	
    //Move the map and zoom
	[self zoomMapAndCenterAtLatitude:latitude andLongitude:longitude];    
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
    span.latitudeDelta  = 0.009;
    span.longitudeDelta = 0.009;
	
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
	self.title = @"Walk-in Centre" ;
	estado_siguiente = -1 ;
	estado_anterior  = 3 ; //Voy a volver desde el mapa
	
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

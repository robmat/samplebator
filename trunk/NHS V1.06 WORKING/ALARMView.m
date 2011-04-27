//
//  ALARMView.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 01/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "ALARMView.h"
#import<AVFoundation/AVAudioPlayer.h>
#import "AnimatedGif.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import "PlacemarkViewController.h"
#import "customCell.h"

NSString *path ;
AVAudioPlayer* theAudio ;

BOOL alarmaSonando ;

@implementation ALARMView

@synthesize mapa, reverseGeocoder, sendSMSButton, lista;

//NSString *CellIdentifier = @"CustomCell";

-(NSString *) dataFilePath{
	
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString * documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:kFilename ] ;
	
}


-(IBAction) buttonAlarmTurnAction{

	if ( alarmaSonando ){
		
		UIAlertView * alert =	[[UIAlertView alloc] initWithTitle:@"NHS Bristol: ICE" message:@"Are you sure you want to turn the alarm off?" delegate:self cancelButtonTitle:@"Cancel" otherButtonTitles:@"Continue", nil];
		[alert show];
		[alert release];

	}
	else{
		[states replaceObjectAtIndex:7 withObject:   @"On" ] ;   //Alarm sound on or off
		[myTableView reloadData] ;
		
		[theAudio play];
		buttonAlarmTurn.title = @"ALARM OFF" ;
		alarmaSonando = TRUE ;
		//theAnimatedGif.hidden = FALSE ;
		myTimer = [[NSTimer timerWithTimeInterval:.025 target:self selector:@selector(timerFired:) userInfo:nil repeats:YES] retain];
		[[NSRunLoop currentRunLoop] addTimer:myTimer forMode:NSDefaultRunLoopMode];
	}
	
}

-(void)alertView:(UIAlertView *) alertView clickedButtonAtIndex: (NSInteger)buttonIndex {
	
	//Activo el temportizador para cerrar la ventana de loading
	//myTimer = [[NSTimer timerWithTimeInterval:.025 target:self selector:@selector(timerFired:) userInfo:nil repeats:YES] retain];
	//[[NSRunLoop currentRunLoop] addTimer:myTimer forMode:NSDefaultRunLoopMode];
	
	//[myTimer invalidate] ;
	
	if (buttonIndex == 0){
		//Cancel Button		
		
		if (volver) {
			[[self navigationController] popToRootViewControllerAnimated:YES ] ;
			volver = FALSE ;
		}
		
	

	}
	if (buttonIndex == 1){
		//Acept button
		
		if(call999boolean){
			[[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"tel:999"]] ;
		}		
		else{	
			[states replaceObjectAtIndex:7 withObject:   @"Off" ] ;   //Alarm sound on or off
			[myTableView reloadData] ;
		
			buttonAlarmTurn.title = @"ALARM ON" ;
			call999boolean = FALSE ;
			[theAudio stop];
			NSLog(@"Turning OFF the alarm!") ;
			alarmaSonando = FALSE ;
			theAnimatedGif.hidden = TRUE ;
			volver = FALSE ;
		
			[myTimer invalidate] ;
			imageRedBackground.alpha = 0.0 ;
		}
		
	}
	
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


// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
    [super viewDidLoad];
	call999boolean = FALSE ;
	aumentar = TRUE ;
	topeMAX = 1 ;
	topeMIN = 0.0 ;
	
	tempoText = [[NSString alloc] init] ;
	volver = FALSE ;
		
	buttonAlarmTurn.enabled = TRUE ;
	
	capitals = [[NSMutableArray alloc] initWithObjects: @"Name",@"DOB", @"Adress",@"Blood",@"Allergies",@"Medication",@"Existing conditions",@"Next of kin", @"Alarm sound", nil ] ;
	states = [[NSMutableArray alloc] initWithObjects: @"Your name",@"Your DOB", @"Your adress",@"Your blood", @"Your allergies", @"Your medication", @"Your existing conditions" , @"Kin information", @"Off", nil ] ; 			   
	
	myTableView.rowHeight = 70.0 ;
	
	path = [[NSBundle mainBundle] pathForResource:@"Alarm1" ofType:@"mp3"];
	theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	
	buttonAlarmTurn.title = @"ALARM OFF" ;
	alarmaSonando = TRUE ; 
	

	NSString * filePath = [self dataFilePath ] ;
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSArray * array = [[NSArray alloc] initWithContentsOfFile:filePath] ;
		//Cargo el nombre en el indice cero (recordemos que states es la informacion del usuario)
		[states replaceObjectAtIndex:0 withObject:   [array objectAtIndex:0]  ] ;   // Name
		[states replaceObjectAtIndex:1 withObject:   [array objectAtIndex:8]  ] ;   // DOB	
		[states replaceObjectAtIndex:2 withObject:   [array objectAtIndex:1]  ] ;   // Adress
		[states replaceObjectAtIndex:3 withObject:   [array objectAtIndex:2]  ] ;   // Blood type
		[states replaceObjectAtIndex:4 withObject:   [array objectAtIndex:9]  ] ;   // ALERGIES	
		[states replaceObjectAtIndex:5 withObject:   [array objectAtIndex:10] ] ;   // MEDICATION	
		[states replaceObjectAtIndex:6 withObject:   [array objectAtIndex:13] ] ;   // Existing conditions
		[states replaceObjectAtIndex:7 withObject:   [array objectAtIndex:5]  ] ;   // Next of kin name
		[states replaceObjectAtIndex:8 withObject:   [array objectAtIndex:11] ] ;   //Alarm sound on or off

		[array release] ;
		[myTableView reloadData] ;
	}

	mostrarAlerta = FALSE ;
	NSString * tempoString ;
	tempoString = [[NSString alloc] init] ;
	
	
	//Compruebo que el usuario ha introducido todos sus datos:
	tempoString = [states objectAtIndex:0] ;
	
	if ([([states objectAtIndex:0]) isEqualToString:@"Your name"] ) {
		mostrarAlerta = TRUE ;
	}
	if ([([states objectAtIndex:1]) isEqualToString:@"Your date of birth"] ) {
		mostrarAlerta = TRUE ;
	}
	if ([([states objectAtIndex:2]) isEqualToString:@"Your address"] ) {
		mostrarAlerta = TRUE ;
	}
	if ([([states objectAtIndex:3]) isEqualToString:@"Your blood type"] ) {
		mostrarAlerta = TRUE ;
	}
	if ([([states objectAtIndex:7]) isEqualToString:@"Your kin name"] ) {
		mostrarAlerta = TRUE ;
	}	
	
	
	if(mostrarAlerta){
	
		NSLog(@"ALERTA!!") ;
		volver = TRUE ;
		UIAlertView * alert =	[[UIAlertView alloc] initWithTitle:@"NHS Bristol: ICE" message:@"You need to set your information in the ICE Settings (Personal menu) before turning on the Alarm." delegate:self cancelButtonTitle:@"Ok" otherButtonTitles: nil];
		[alert show];
		[alert release];

		buttonAlarmTurn.enabled = FALSE ;

		//Deshabilitar botones:
		
		
	}
	else {
		NSLog(@"Alarma estado: %@", [states objectAtIndex:8] ) ;
		if( [([states objectAtIndex:8]) isEqualToString:@"On"] ){
			alarmaSonando = TRUE ;
			buttonAlarmTurn.title = @"ALARM OFF" ;
			theAudio.numberOfLoops = 20 ;
			[theAudio play];

			//Activo el temportizador para cerrar la ventana de loading
			myTimer = [[NSTimer timerWithTimeInterval:.025 target:self selector:@selector(timerFired:) userInfo:nil repeats:YES] retain];
			[[NSRunLoop currentRunLoop] addTimer:myTimer forMode:NSDefaultRunLoopMode];
		}
		else{
			alarmaSonando = FALSE ;
			buttonAlarmTurn.title = @"ALARM ON" ;
		}
	}
	
	self.title = @"ICE" ;
	
	//Activo el temportizador para cerrar la ventana de loading
	myTimer = [[NSTimer timerWithTimeInterval:.025 target:self selector:@selector(timerFired:) userInfo:nil repeats:YES] retain];
	//[[NSRunLoop currentRunLoop] addTimer:myTimer forMode:NSDefaultRunLoopMode];

	//[myTimer invalidate] ;
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


-(void)viewWillAppear:(BOOL)animated{

	mapa.showsUserLocation = TRUE ;
 }	



-(IBAction)buttonTest{

	NSString * email = [NSString alloc] ;
	email = @"mailto://leopoldo.romacho@gmail.com?Subject=Hola&body=Cuerpo del mensaje" ;
	
	email = [email stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
	[[UIApplication sharedApplication] openURL:[NSURL URLWithString:email]] ;
	

}

- (IBAction)sendSMS{

    
	float latitudeFLOAT = mapa.userLocation.coordinate.latitude ;
		
	if( latitudeFLOAT == -180.000000 ){
		NSLog(@"No tenemos las posiciones GPS del usuario. Lanzamos mensaje." ) ;
		
		//No ha sido posible tomar tus coordenadas GPS.
		UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"NHS Bristol" message: @"We didn't found your GPS position. Please check your connection." delegate:self cancelButtonTitle:@"OK" otherButtonTitles: nil];
		
		[alert show];
		[alert release];
		
	}
	else{

		//Hago la conversion de las coordenadas:
		self.reverseGeocoder =
		[[[MKReverseGeocoder alloc] initWithCoordinate:mapa.userLocation.location.coordinate] autorelease];
		reverseGeocoder.delegate = self;
		[reverseGeocoder start];
		
	}

}

- (void)reverseGeocoder:(MKReverseGeocoder *)geocoder didFailWithError:(NSError *)error
{
    NSLog(@"MKReverseGeocoder has failed.");
}

- (void)reverseGeocoder:(MKReverseGeocoder *)geocoder didFindPlacemark:(MKPlacemark *)placemark
{
	// Si he entrado aqui, significa que puedo enviar mi posicion: (Direccion + coordenadas)
	NSLog(@"Las coordenadas se han convertido a texto perfectamente.") ;
	NSLog(@"        Postcode: %@ ", placemark.postalCode ) ;	
	NSLog(@"        Locality: %@ ", placemark.locality ) ;	
	NSLog(@"        Country: %@ ", placemark.country ) ;	
	NSLog(@"        Latitude: %f ", mapa.userLocation.coordinate.latitude ) ;	
	NSLog(@"        Longitude: %f ", mapa.userLocation.coordinate.longitude ) ;		

	NSString * header = [NSString alloc] ;
	NSString * message = [NSString alloc] ;
	NSString * mailNextkin = [NSString alloc] ;
	NSString * email = [NSString alloc] ;

	header = @"NHS Bristol: This is an emergency message. My position: " ;
	message = [NSString stringWithFormat:@"%@%@ %@ %@. Coordinates: %f %f", header, placemark.postalCode, placemark.locality, placemark.country, mapa.userLocation.coordinate.latitude, mapa.userLocation.coordinate.longitude ] ;
	NSString * filePath = [self dataFilePath ] ;

	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSArray * array = [[NSArray alloc] initWithContentsOfFile:filePath] ;
		mailNextkin = [array objectAtIndex:7] ; 
	}

	email = [NSString stringWithFormat: @"%@%@%@" ,  @"mailto:" , mailNextkin, @"?Subject=NHS Bristol: Help me!&body=" ] ;
	email = [NSString stringWithFormat: @"%@%@.\n\nSee in google maps: http://maps.google.com/maps?q=%f,%f", email, message, mapa.userLocation.coordinate.latitude, mapa.userLocation.coordinate.longitude ] ; 
	email = [email stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
	[[UIApplication sharedApplication] openURL:[NSURL URLWithString:email]] ;
	
}

-(IBAction)smsContinueAction{

}

-(IBAction)call999{
	UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"ICE" message:@"Are you sure you want to call 999 emergencies?"
												   delegate:self cancelButtonTitle:@"Cancel" otherButtonTitles: @"Ok", nil];
	call999boolean = TRUE ;
	[alert show];
	[alert release];
}


- (void)mapView:(MKMapView *)mapa didAddAnnotationViews:(NSArray *)views
{
    // we have received our current location, so enable the "Get Current Address" button
    //[sendSMSButton setEnabled:YES];
	//sendSMSButton.enabled = YES ;
}





-(void)viewWillDisappear:(BOOL)animated{
	
	[theAudio stop];
	
	//Button Sound
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
	
	//[animation release] ;
}	

- (void)dealloc {

	//[theAnimatedGif release] ;
	//[theAudio release] ; 

    [super dealloc];
	
	
	
	
}

#pragma mark Table view methods

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}


// Customize the number of rows in the table view.
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return ([states count]-1);
}


// Customize the appearance of table view cells.
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    static NSString *CellIdentifier = @"CustomCell";
	
    CustomCell *cell = (CustomCell *) [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
	
    
	if (cell == nil) {
		
		NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"CustomCell" owner:self options:nil];
		
		for (id currentObject in topLevelObjects){
			if ([currentObject isKindOfClass:[UITableViewCell class]]){
				if(cell.textLabel.text != @"Alarm sound"){
					cell =  (CustomCell *) currentObject;
					cell.detailButton.alpha = 0 ;				
					break;
				}	
			}
		}
	}
	
	cell.autoresizesSubviews = YES ;
	
	
	
	
	
	//Carga los datos en los arrays de la pantalla
	cell.capitalLabel.text = [capitals objectAtIndex:indexPath.row];
	cell.stateLabel.text = [states objectAtIndex:indexPath.row];
	cell.detailButtonRound.alpha = 0 ;
	
	if ([cell.capitalLabel.text isEqualToString:@"Medication"]) {
		[cell.detailButtonRound addTarget:self action: @selector(buttonMedication:) forControlEvents:UIControlEventTouchUpInside ] ;

		tempoText = cell.stateLabel.text ;
 		cell.detailButton.alpha = 0 ;
		cell.detailButtonRound.alpha = 1 ;

	}

	if ([cell.capitalLabel.text isEqualToString:@"Allergies"]) {
		[cell.detailButtonRound addTarget:self action: @selector(buttonAllergies:) forControlEvents:UIControlEventTouchUpInside ] ;
		cell.detailButton.alpha = 0 ;
		cell.detailButtonRound.alpha = 1 ;
	}
	
	if ([cell.capitalLabel.text isEqualToString:@"Existing conditions"]) {
		[cell.detailButtonRound addTarget:self action: @selector(buttonexistingConditions:) forControlEvents:UIControlEventTouchUpInside ] ;
		cell.detailButton.alpha = 0 ;
		cell.detailButtonRound.alpha = 1 ;
	}
	
	//En state tengo la informacion del usuario
	
	//ceel.stateLabel.text = [states
	
	
	
	
	//cell.capitalLabel.text = [ @"HOOLAA" objectAtIndex:0 ] ;
	
	
	//IMPORTANT::We are going to load the information from the Memory array:
	
	NSString * filePath = [self dataFilePath ] ;
	
	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		
		//NSArray * array = [[NSArray alloc] initWithContentsOfFile:filePath] ;
		//nameLabel.text = [array objectAtIndex:0] ;
		//dobLabel.text = [array objectAtIndex:1] ;
		//nextkinLabel.text = [array objectAtIndex:2] ;
		//bloodLabel.text = [array objectAtIndex:3] ;
		//[array release] ;
	}	
	
	
    return cell;
}



- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	
	NSString * str = [ capitals  objectAtIndex:indexPath.row ] ;
	
	if ( [str isEqual:@"Allergies" ] ) {
			

		
	}	
	if ( [str isEqual:@"Medication" ] ) {

		
	}	
	
}

-(IBAction)closeNewView{
	[vistaNewView removeFromSuperview ] ;

}

-(IBAction)	buttonMedication:(id)sender{
	textViewNewView.text = tempoText ;
	tituloNewView.text = @"Medication" ;
	[self.view addSubview:vistaNewView ] ;
	
	mainText.text = @"Medication" ;
	insideText.text = [states objectAtIndex:5] ;

}

-(IBAction)	buttonAllergies:(id)sender{
	textViewNewView.text = tempoText ;
	tituloNewView.text = @"Medication" ;
	[self.view addSubview:vistaNewView ] ;
	
	mainText.text = @"  Allergies" ;
	insideText.text = [states objectAtIndex:4] ;
	
}

-(IBAction)	buttonexistingConditions:(id)sender{
	//textViewNewView.text = tempoText ;
	//tituloNewView.text = @"Existing conditions" ;
	[self.view addSubview:vistaNewView ] ;
	
	mainText.text = @"Conditions" ;
	insideText.text = [states objectAtIndex:6] ;
	
}


- (void)timerFired:(NSTimer *)timer{
	
	
	if(aumentar){
		if(imageRedBackground.alpha  > topeMAX){
			aumentar = FALSE ;
		}
		else{
			imageRedBackground.alpha += 0.025 ;
		}
	}
	else {
		if(imageRedBackground.alpha  < topeMIN){
			aumentar = TRUE ;
		}
		else{
			imageRedBackground.alpha -= 0.025 ;
		}
	}
	NSLog(@"%f", imageRedBackground.alpha ) ;
	
}



-(IBAction)changeColourBackgroundAction:(id)sender{

	

}


@end


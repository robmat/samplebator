//
//  View2Controller.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 12/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "View2Controller.h"
#import "ViewEmergenciesMap.h"
#import "ViewWalkinMap.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import "View1Controller.h"
#import "confirmationWalkin.h"
#import "ConfirmationSexHealth.h"
#import <AVFoundation/AVAudioPlayer.h>
#import "GPMap.h"
#import "HospitalMAP.h"
#import "PharmacyMAP.h"
#import "dentalMAP.h"
#import "SexHealthMapController.h"

@implementation View2Controller
int element_index ;
//: UIViewController

-(IBAction)HospitalButton{
	estado_anterior = 2 ; // Estoy en service finder, donde estoy ahora 
	estado_siguiente = 3 ; // Voy al mapa
	
	avanzar = TRUE ;

	hospitalMAP * varhospitalMAP = [[hospitalMAP alloc] initWithNibName:@"hospitalMAP" bundle:nil ] ;
	[[self navigationController] pushViewController:varhospitalMAP animated:YES];
	[varhospitalMAP release] ;	
}

-(IBAction)DentalButton{
	
	UIAlertView * alert =	[[UIAlertView alloc] initWithTitle:@"NHS Yorkshire and Humber: Dentist" message:@"To find a dentist accepting NHS patients, call the NHS Yorkshire and Humber Dental Helpline on: 0845 120 6680. The line is open between 9 a.m. and 6 p.m. Monday to Fridays." delegate:self cancelButtonTitle:@"Cancel" otherButtonTitles:@"Continue", nil];
	
	element_index = 2 ; // I have pressed the button 'Dentist' button
	[alert show];
	[alert release];
}



-(IBAction)PharmacistButton{

	estado_anterior = 2 ; // Estoy en service finder, donde estoy ahora 
	estado_siguiente = 3 ; // Voy al mapa
	avanzar = TRUE ;	
	
	PharmacyMAP * varPharmacyMAP = [[PharmacyMAP alloc] initWithNibName:@"PharmacyMAP" bundle:nil ] ;
	[[self navigationController] pushViewController:varPharmacyMAP animated:YES];
	[varPharmacyMAP release] ;	
	
}

-(IBAction)AandE999Button{
	// open a alert with an OK and cancel button
	
 	UIAlertView * alert =	[[UIAlertView alloc] initWithTitle:@"NHS Yorkshire and Humber: Is it a critical situation?" message:@"A critital situation can include: unconsciousness, a suspected stroke, heavy blood loss, a deep wound such as a stab wound, a suspected heart attack, difficulty in breathing or severe burns." delegate:self cancelButtonTitle:@"Cancel" otherButtonTitles:@"Continue", nil];
	element_index = 0 ; // I have pressed the button 'AandE999' button
	[alert show];
	[alert release];
	
}

-(IBAction)WalkinCenterButton{
	
	avanzar = TRUE ;	
	estado_anterior = 2 ; // Vengo de aqui, donde estoy ahora
	estado_siguiente = 4 ; // Voy a confirmar el walkin center
	
	confirmationWalkin * varconfirmationWalkin = [[confirmationWalkin alloc] initWithNibName:@"confirmationWalkin" bundle:nil ] ;
	[[self navigationController] pushViewController:varconfirmationWalkin animated:YES];
	[varconfirmationWalkin release] ;	
	
}

-(IBAction)GPButton{

	estado_anterior = 2 ; // Estoy en service finder, donde estoy ahora 
	estado_siguiente = 3 ; // Voy al mapa
	avanzar = TRUE ;		
	
	GPMap * varGPMap = [[GPMap alloc] initWithNibName:@"GPMap" bundle:nil ] ;
	//SexHealthMapController* varGPMap = [[SexHealthMapController alloc] initWithNibName:@"SexHealthMap" bundle:nil ] ;
	[[self navigationController] pushViewController:varGPMap animated:YES];
	[varGPMap release] ;	
}

-(IBAction)SexHealthButton{
	
	avanzar = TRUE ;	
	estado_anterior = 2 ; // Vengo de aqui, donde estoy ahora
	estado_siguiente = 4 ; // Voy a confirmar el walkin center
	
	ConfirmationSexHealth* confSexHealth = [[ConfirmationSexHealth alloc] initWithNibName:@"ConfirmationSexHealth" bundle:nil ] ;
	[[self navigationController] pushViewController:confSexHealth animated:YES];
	[confSexHealth release] ;
	
	
}

-(IBAction)linkButtonPressed:(id)sender{
	NSLog(@"Link to 4yp");
}

-(void)alertView:(UIAlertView *) alertView clickedButtonAtIndex: (NSInteger)buttonIndex {
	
	if (buttonIndex == 0){
		//Cancel Button			
		
	}
	if (buttonIndex == 1){
		//ContinueButton 
		// Emergencies Button And Continue option pressed
		if ( element_index == 0 ) { 
			
			estado_anterior = 2 ; // Estoy en service finder, donde estoy ahora 
			estado_siguiente = 3 ; // Voy al mapa
			
			avanzar = TRUE ;			
			ViewEmergenciesMap * varViewEmergenciesMap = [[ViewEmergenciesMap alloc] initWithNibName:@"ViewEmergenciesMap" bundle:nil ] ;
			[[self navigationController] pushViewController:varViewEmergenciesMap animated:YES];
			[varViewEmergenciesMap release] ;
			
			
		}	
		// Emergencies Button And Continue option pressed		
		if (element_index == 2) {

			estado_anterior = 2 ; // Estoy en service finder, donde estoy ahora 
			estado_siguiente = 3 ; // Voy al mapa
			avanzar = TRUE ;	
			
			dentalMAP * vardentalMAP = [[dentalMAP alloc] initWithNibName:@"dentalMAP" bundle:nil ] ;
			[[self navigationController] pushViewController:vardentalMAP animated:YES];
			[vardentalMAP release] ;	
		}
		// Walk-in Centers Button And Continue option pressed
		if ( element_index == 1 ) { 
			
			imagenTermo.hidden = TRUE ;
			avanzar = TRUE ;
			
			ViewWalkinMap * varViewWalkinMap = [[ViewWalkinMap alloc] initWithNibName:@"ViewWalkinMap" bundle:nil ] ;
			[[self navigationController] pushViewController:varViewWalkinMap animated:YES];
			[varViewWalkinMap release] ;
		}	
		
		if(element_index == 3){
			estado_anterior = 2 ; // Estoy en service finder, donde estoy ahora 
			estado_siguiente = 3 ; // Voy al mapa
			
			avanzar = TRUE;
			
			SexHealthMapController* varSHMap = [[SexHealthMapController alloc] initWithNibName:@"SexHealthMap" bundle:nil ] ;
			[[self navigationController] pushViewController:varSHMap animated:YES];
			[varSHMap release] ;
		}
	}
}	

/*
-(IBAction)WalkinCenterButton:(id)sender{
	NSLog(@"ok") ;
	
	[[UIApplication sharedApplication] openURL:myURL] ;
	// open a alert with an OK and cancel button
	
	UIAlertView *alertWalkinCenter = [[UIAlertView alloc] initWithTitle:@"Walk-in Centers: Do you want continue?" message:@"Walk-in centers::Text that indicates what is a walk-in or not. If you are sure that must continue, please press the Continue button."
												   delegate:self cancelButtonTitle:@"Cancel" otherButtonTitles:@"Continue", nil];
	[alertWalkinCenter show];
	[alertWalkinCenter release];

}
*/


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
	
	if ((estado_anterior == 61) && (estado_siguiente == 61)) { //Si es la visita del comienzo
		estado_anterior = -1 ;
		estado_siguiente = -1 ;
		imagenTermo.hidden = FALSE ;
		view2Bar.hidden = FALSE ;	
		[[self navigationController] popToRootViewControllerAnimated:NO ] ;
	}
	
	avanzar = FALSE ;
	
	
	
	self.title = @"Service Finder" ;
	view2Bar.hidden = NO ;
	
	//UIBarButtonItem * homeButton = [[[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"Home", @"")	style: UIBarButtonItemStyleBordered target:self action:@selector(GoHome:)] autorelease ] ;
	//self.navigationItem.rightBarButtonItem = homeButton ;
	
	
	//NSLog(@"ViewdidLoad Service finder *****CARGADO*******") ;

	UIBarButtonItem * homeButton = [[[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"Home", @"")	style: UIBarButtonItemStylePlain /*UIBarButtonItemStyleBordered*/ target:self action:@selector(goHome:)] autorelease ] ;
	

	self.navigationItem.rightBarButtonItem = homeButton ;

	//self.navigationItem.rightBarButtonItem.image = [UIImage imageNamed:@"homeIcon.gif"];
	

	//self.navigationItem.rightBarButtonItem.customView.backgroundColor = [UIColor clearColor ] ;

	
	//[[self.navigationController navigationBar] setBackgroundImage: homeButtonPicture ] ;	
	
	
	
	
	UIBarButtonItem * backBar = [[UIBarButtonItem alloc] initWithTitle:@"Backaaaa" style: UIBarButtonItemStyleDone target: nil action: nil ] ;
	self.navigationController.navigationItem.backBarButtonItem = backBar ;
	

}


/*
// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}
*/

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


//button BACK pressed
//__________________________________________________________________________________________________________________________________________________

-(void)viewWillDisappear:(BOOL)animated{

	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
	
	
	
	if(estado_siguiente == -1 ){ //El boton back ha sido pulsado
		NSLog(@"Back") ;
		view2Bar.hidden = NO ;
		imagenTermo.hidden = FALSE ;
	}
	
	if ((estado_siguiente == 3) && (estado_anterior == 2 )) {
		NSLog(@"Desde SERVICES FINDER avanzo hasta MAP EMERGENCIES") ;
		view2Bar.hidden = FALSE ;
		imagenTermo.hidden = TRUE ;	

	}
	
	if ((estado_siguiente == 4) && (estado_anterior == 2 ) ){
		NSLog(@"Desde SERVICES FINDER avanzo hasta CONFIRMATION WALKIN") ;
		view2Bar.hidden = FALSE ;
		imagenTermo.hidden = FALSE ;

	}
	
	if ( (estado_anterior == 1 ) && ( estado_siguiente == 2 )){ // Tal como he llegado desde el menu principal, he pulsado back
		view2Bar.hidden = NO ;
		imagenTermo.hidden = FALSE ;	
		
	}
	
	if (avanzar == TRUE) {
		self.title = @"Back" ;
	}	

	
}

-(void)viewWillAppear:(BOOL)animated{

	self.title = @"Service Finder" ;
	if( (estado_anterior == 3) && (estado_siguiente == -1)){ //si estoy en back
		view2Bar.hidden = FALSE ;
		imagenTermo.hidden = TRUE ;
	}	
	
	if( (estado_anterior == 4) && (estado_siguiente == -1) ){
		view2Bar.hidden = NO ;
		imagenTermo.hidden = FALSE;
		
	}

	if((estado_anterior == 1) && (estado_siguiente == 2) ){ //Vengo del menu principal 
		view2Bar.hidden = NO ;
		imagenTermo.hidden = FALSE ;
		
		NSLog(@"GO: Vengo del menu de MENU_PRINCIPAL y ahora estoy en SERVICEFINDER") ;
		
	}


	if((estado_anterior == 4) && (estado_siguiente == -1) ){ //Vengo del menu "confirmacion walkin" (Con boton Back)
		view2Bar.hidden = NO ;
		imagenTermo.hidden = FALSE ;
		
		NSLog(@"BACK: Vengo del menu de CONFIRMACION_WALKIN y ahora estoy en SERVICEFINDER") ;
	}
	if((estado_anterior == -1) && (estado_siguiente == -1) ) {
		view2Bar.hidden = NO;
	}
	avanzar = FALSE ;
	
}

-(IBAction)goHome:(id)sender{
	[[self navigationController] popToRootViewControllerAnimated:YES ] ;
}


@end

//
//  View1Controller.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 12/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "View1Controller.h"
#import "View2Controller.h"
#import "browserNHSView.h"
#import "browserNHSView2.h"
#import "ALARMView.h"
#import "helpMeView.h"
#import "personalViewSubMenu.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import "appointmentsViewController.h"
#import "contactNSHView.h"
#import <AVFoundation/AVAudioPlayer.h>
#import "UINavigationBar+CustomImage.h"
#import <AudioToolbox/AudioToolbox.h>
#import "audiosView.h"
#import "StopSmokingMapViewController.h"


@implementation View1Controller

-(IBAction)StopSmokingButton:(id) sender {
	imagenTermo.hidden = TRUE ;
	StopSmokingMapViewController* ssvc = [[StopSmokingMapViewController alloc] initWithNibName:nil bundle:nil];
	[[self navigationController] pushViewController:ssvc animated:YES];
	[ssvc release];
}

-(IBAction)HelpMeButton:(id)sender{
	imagenTermo.hidden = TRUE ;
	ALARMView * varALARMView = [[ALARMView alloc] initWithNibName:@"ALARMView" bundle:nil ] ;
	[[self navigationController] pushViewController:varALARMView animated:YES];
	[varALARMView release] ;
	
}

-(IBAction)ServiceFinderButton:(id)sender{
	
	estado_anterior = 1 ;
	estado_siguiente = 2 ;
	
	imagenTermo.hidden = FALSE ;
	
	View2Controller * varView2Controller = [[View2Controller alloc] initWithNibName:@"View2Controller" bundle:nil ] ;
	[[self navigationController] pushViewController:varView2Controller animated:YES];
	[varView2Controller release] ;
}

-(IBAction)NHSDirectButton:(id)sender{
	
	//fromWhere = 1 ; // Vengo del view1

	//imagenTermo.hidden = TRUE ;
	
	browserNHSView2 * varbrowserNHSView2 = [browserNHSView2 alloc]; 
	varbrowserNHSView2.urlString = @"http://www.nhsdirect.nhs.uk";
	varbrowserNHSView2.title = @"NHS Direct";
	[varbrowserNHSView2 initWithNibName:@"browserNHSView2" bundle:nil ] ;
	[[self navigationController] pushViewController:varbrowserNHSView2 animated:YES];
	[varbrowserNHSView2 release] ;
	
}


-(IBAction)PersonalButton:(id)sender{
	
	imagenTermo.hidden = TRUE ;
		
	personalViewSubMenu * varpersonalViewSubMenu = [[personalViewSubMenu alloc] initWithNibName:@"personalViewSubMenu" bundle:nil ] ;
	[[self navigationController] pushViewController:varpersonalViewSubMenu animated:YES];
	[varpersonalViewSubMenu release] ;
}

-(IBAction)AppointmentsButton:(id)sender{
	
	imagenTermo.hidden = TRUE ;

	audiosView * varaudiosView = [[	audiosView alloc] initWithNibName:@"audiosView" bundle:nil ] ;
	[[self navigationController] pushViewController:varaudiosView animated:YES];
	[varaudiosView release] ;
}

-(IBAction)contactNHSView:(id)sender{

	imagenTermo.hidden = TRUE ;
	
	contactNSHView * varcontactNSHView = [[contactNSHView alloc] initWithNibName:@"contactNSHView" bundle:nil ] ;
	[[self navigationController] pushViewController:varcontactNSHView animated:YES];
	[varcontactNSHView release] ;	
	
	//[[self navigationController] topViewController animated:YES ] ;  
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
	
	[self.view addSubview:loadingView ] ;
	
	self.title = @" " ;
	
	self.navigationController.navigationBar.alpha = 0 ;
	
	/*
	SOSBut.enabled = FALSE ;
	ServicesBut.enabled = FALSE ;  
	NHSDirectBut.enabled = FALSE ;
	PersonalBut.enabled = FALSE ; 
	RemindersBut.enabled = FALSE ; 
	ContactNHSBut.enabled = FALSE ; 	
	*/
	
	imageLoading.image = nil ;
	
	imagenTermo.hidden = TRUE ;
	viewBar.hidden = TRUE ;
	[activity startAnimating] ;
	
	//Activo el temportizador para cerrar la ventana de loading
	myTimer = [[NSTimer timerWithTimeInterval:3.0 target:self selector:@selector(timerFired:) userInfo:nil repeats:NO] retain];
	[[NSRunLoop currentRunLoop] addTimer:myTimer forMode:NSDefaultRunLoopMode];
	
	mapa.showsUserLocation = TRUE ;
	[mapa release] ; 
	
	[super viewDidLoad];
	
	
	imageNHS =  [UIImage imageNamed:@"NHS logo 7 - 319x43 V3.png"];
	
	self.title = @"NHS BRISTOL" ;

	UIBarButtonItem * backBar = [[UIBarButtonItem alloc] initWithTitle:@"Backaaaa" style: UIBarButtonItemStyleDone target: nil action: nil ] ;
	self.navigationController.navigationItem.backBarButtonItem = backBar ;

	//NSLog(@"Leo latitude: %f", mapa.userLocation.coordinate.latitude) ;
 	//NSLog(@"Leo longitude: %f", mapa.userLocation.coordinate.longitude) ;	

}



- (void)timerFired:(NSTimer *)timer{
	
	estado_anterior = 61 ;
	estado_siguiente = 61 ;
	
	
	
	self.navigationController.navigationBar.alpha = 1 ;
	self.title = @"NHS Bristol" ;
	
	NSLog(@"SALTA EL TEMPORTIZADOR!! -  Hola maigo!") ;

	loadingLabel.text = @"" ;
	NHSBristolLabel.text = @"" ;

	imagenTermo.hidden = FALSE ;
	viewBar.hidden = FALSE ;
	
	[activity stopAnimating] ;

	/*
	SOSBut.enabled = TRUE ;
	ServicesBut.enabled = TRUE ;  
	NHSDirectBut.enabled = TRUE ;
	PersonalBut.enabled = TRUE ; 
	RemindersBut.enabled = TRUE ; 
	ContactNHSBut.enabled = TRUE ;
	 */
	
	[loadingView removeFromSuperview] ;
	
	[self.navigationController release] ;
	[imageLoading release] ;

	//Voy a hacer avanzar y a retroceder para actualizar la pantalla! (Y asi mostrar los botones ordenados):
	imagenTermo.hidden = FALSE ;

	//[helpView removeFromSuperview ] ;
	
	View2Controller * varView2Controller = [[View2Controller alloc] initWithNibName:@"View2Controller" bundle:nil ] ;
	[[self navigationController] pushViewController:varView2Controller animated:NO];
	[varView2Controller release] ;

}

/*
// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}
*/

-(IBAction)showMeDoctorHelpAction:(id)sender{
	helpTexto.alpha = 0 ;
	toolbar.alpha = 0 ;
	showMeDoctorHelpButton.alpha = 0 ;
	[self.view addSubview: helpView ] ;
	

}

-(IBAction)showMeDoctorHelpMeYES:(id)sender{
	helpTexto.alpha = 1 ;
	toolbar.alpha = 1 ;
	imagenTermo.hidden = TRUE ;
	showMeDoctorHelpButton.alpha = 1 ;
	viewBar.hidden = TRUE ;	

	
}	

-(IBAction)showMeDoctorHelpMeNO:(id)sender{
	helpTexto.alpha = 0 ;
	imagenTermo.hidden = FALSE ;
	viewBar.hidden = FALSE ;		
	showMeDoctorHelpButton.alpha = 1 ;
	[helpView removeFromSuperview];

}

-(IBAction)goHome:(id)sender{
	[self.navigationController popToRootViewControllerAnimated:NO];
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

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
	return NO;
}

-(void)viewDidAppear:(BOOL)animated{

	//Si vengo de los mapas, y quiero mostrar el browser, lo hago.
	if ( (estado_anterior == 51) && (estado_siguiente == 51) ) {
		
		browserNHSView2 * varbrowserNHSView2 = [browserNHSView2 alloc]; 
		varbrowserNHSView2.urlString = @"http://www.nhsdirect.co.uk";
		varbrowserNHSView2.title = @"NHS Direct";
		[varbrowserNHSView2 initWithNibName:@"browserNHSView2" bundle:nil ] ;
		[[self navigationController] pushViewController:varbrowserNHSView2 animated:YES];
		[varbrowserNHSView2 release] ;
		 
	} 
}

-(void)viewWillAppear:(BOOL)animated{
	
	viewBar.hidden = FALSE;
	
}

-(void)viewWillDisappear:(BOOL)animated{
	
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
		
	if( estado_anterior != 61){
		[theAudio play];
	}
	NSString * latitudeLABEL = [[NSString alloc] initWithFormat:@"Latitude: %f", mapa.userLocation.coordinate.latitude ] ;
	NSString * longitudeLABEL = [[NSString alloc] initWithFormat:@"Longitude: %f", mapa.userLocation.coordinate.longitude ] ;

	NSLog(@"%@", latitudeLABEL) ;
	NSLog(@"%@", longitudeLABEL) ;	
	
	tempoLabel.text = latitudeLABEL ;
	
}




@end

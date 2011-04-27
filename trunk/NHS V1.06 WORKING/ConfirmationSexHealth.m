    //
//  ConfirmationSexHealth.m
//  PushViewControllerAnimated
//
//  Created by Andrew Farmer on 03/09/2010.
//  Copyright 2010 __MyCompanyName__. All rights reserved.
//

#import "ConfirmationSexHealth.h"
#import "SexHealthMapController.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import <AVFoundation/AVAudioPlayer.h>
#import "browserNHSView2.h"


@implementation ConfirmationSexHealth

/*
 // The designated initializer.  Override if you create the controller programmatically and want to perform customization that is not appropriate for viewDidLoad.
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    if ((self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil])) {
        // Custom initialization
    }
    return self;
}
*/

/*
// Implement loadView to create a view hierarchy programmatically, without using a nib.
- (void)loadView {
}
*/


// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	self.title = @"4YP Services" ;
	UIBarButtonItem * homeButton = [[[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"Home", @"")	style: UIBarButtonItemStyleBordered target:self action:@selector(goHome:)] autorelease ] ;
	self.navigationItem.rightBarButtonItem = homeButton ;
	
    [super viewDidLoad];
	
}

-(IBAction)goto4YPWebsite:(id)sender
{
	browserNHSView2 * varbrowserNHSView2 = [browserNHSView2 alloc]; 
	varbrowserNHSView2.urlString = @"http://www.4ypbristol.co.uk";
	varbrowserNHSView2.title = @"4YP Services";
	[varbrowserNHSView2 initWithNibName:@"browserNHSView2" bundle:nil ] ;
	[[self navigationController] pushViewController:varbrowserNHSView2 animated:YES];
	[varbrowserNHSView2 release] ;
}


-(IBAction)goHome:(id)sender{
	[[self navigationController] popToRootViewControllerAnimated:NO ] ;
}

-(IBAction)continueButton:(id)sender{
	
	avanzar = TRUE ;
	estado_anterior = 4 ;
	estado_siguiente = 3 ;
	
	SexHealthMapController * sexHealthMap = [[SexHealthMapController alloc] initWithNibName:@"SexHealthMap" bundle:nil ] ;
	[[self navigationController] pushViewController:sexHealthMap animated:YES];
	[sexHealthMap release] ;
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

-(void)viewWillDisappear:(BOOL)animated{
	
	if (avanzar == TRUE) {
		self.title = @"Back" ;
	}	
	
	NSLog(@"estado anterior: %i",estado_anterior) ;
	NSLog(@"estado siguiente: %i",estado_siguiente) ;
	
	//Opciones para el boton BACK:
	
	//Opcion1. Vengo del mapa   ->  Si presiono back, mantendre los mismos estados con los que he venido!
	
	//No me preocupo, porque el mapa ha asignado el estado_siguiente a -1.
	if (estado_siguiente == -1) {
		estado_anterior = 4 ;
	}
	
	//Opcion2. Vengo del view2(service Finder, y como recien llegado pulso BACK, mantendre los mismos estados)
	if ((estado_anterior == 2) && (estado_siguiente == 4 )){
		estado_siguiente = -1 ;
		estado_anterior  =  4 ;
	}
	
	
	//Opciones para el boton GO:
	
	//Opcion1. Continuar: (Vengo de confirmacion y voy al mapa)
	if ( (estado_anterior == 4) && (estado_siguiente == 3) ) {
		NSLog(@"GO: Vengo del CONFIRMATIONWALKING y voy al mapa") ;				
		viewBar.hidden = FALSE ;
		imagenTermo.hidden = TRUE ;	
	}
	
	
	if(estado_siguiente == -1){ //si estoy en back y vengo del mapa (o vengo del view2 y quiero volver)
		viewBar.hidden = TRUE ;
		imagenTermo.hidden = FALSE ;
		NSLog(@"SE ACTIVA EL BACK DEL CONFIRMATION WALKING") ;
	}	
	
	//Button Sound
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
	
}

-(void)viewWillAppear:(BOOL)animated{
	
	self.title = @"4YP Services" ;
	
	NSLog(@"estado actual: %i",estado_anterior) ;
	NSLog(@"estado siguiente: %i",estado_siguiente) ;
	
	viewBar.hidden = TRUE ;
	imagenTermo.hidden = FALSE ;

}



- (void)viewDidUnload {
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
}


- (void)dealloc {
    [super dealloc];
}


@end

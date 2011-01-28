#import "confirmationWalkin.h"
#import "ViewWalkinMap.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import<AVFoundation/AVAudioPlayer.h>

@implementation confirmationWalkin

-(IBAction)ContinueButton:(id)sender{

	avanzar = TRUE ;
	estado_anterior = 4 ;
	estado_siguiente = 3 ;
	
	ViewWalkinMap * varViewWalkinMap = [[ViewWalkinMap alloc] initWithNibName:@"ViewWalkinMap" bundle:nil ] ;
	[[self navigationController] pushViewController:varViewWalkinMap animated:YES];
	[varViewWalkinMap release] ;
}


-(IBAction)goHome:(id)sender{
	[[self navigationController] popToRootViewControllerAnimated:NO ] ;
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
    [super viewDidLoad];
	self.title = @"Walk-in Centre" ;
	UIBarButtonItem * homeButton = [[[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"Home", @"")	style: UIBarButtonItemStyleBordered target:self action:@selector(goHome:)] autorelease ] ;
	self.navigationItem.rightBarButtonItem = homeButton ;
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
		viewBar.hidden = NO ;
		imagenTermo.hidden = FALSE ;
		NSLog(@"SE ACTIVA EL BACK DEL CONFIRMATION WALKING") ;
	}	
	
	//Button Sound
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
 
}

-(void)viewWillAppear:(BOOL)animated{
	
	self.title = @"Walk-in Centre" ;
	
	NSLog(@"estado actual: %i",estado_anterior) ;
	NSLog(@"estado siguiente: %i",estado_siguiente) ;
	
	if(estado_siguiente == -1){ //si estoy en back y vengo del mapa
		viewBar.hidden = FALSE ;
		imagenTermo.hidden = TRUE ;
	}
	

		
	if((estado_anterior == 2) && (estado_siguiente == 4) ){ //Vengo del menu "service finder" y vengo aqui ("confirmacion walkin")
		viewBar.hidden = NO ;
		imagenTermo.hidden = FALSE ;
		estado_siguiente = -1 ; //y despues, por defecto, pulsare back 
		NSLog(@"GO: Vengo del SERVICEFINDER y estoy en CONFIRMATIONWALKING") ;
		NSLog(@"estado siguiente: %i",estado_siguiente) ;
	}
	
}


@end

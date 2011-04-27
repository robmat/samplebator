

#import "viewWalkinMapDetails.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import <AVFoundation/AVFoundation.h>

@implementation viewWalkinMapDetails

-(NSString *) dataFilePathWalkin{
	
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString * documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:kFilenameWalkin ] ;
	
}

-(IBAction)goHome:(id)sender{
	[[self navigationController] popToRootViewControllerAnimated:NO ] ;
}

-(IBAction)sendMail:(id)sender{
	
	NSString * email = [[NSString alloc] init ] ;
	
	email = @"mailto:" ;
	
	informacion = [[NSString alloc] init] ;
	informacion	= [NSString stringWithFormat:@"Name of the Walk-in centre: %@. Adress: %@ %@, %@. Telephone number: %@",labelNombre.text, labelAddress1.text, labelAddress1b.text, labelAddress2.text, labelPhone.text ] ;
	
	informacion = [informacion stringByReplacingOccurrencesOfString:@"(null)" withString:@""] ;

	email = [NSString stringWithFormat: @"%@?Subject=NHS Bristol: Walk-in centre information&body=%@", email, (@"%@", informacion)] ; 
	
	email = [email stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
	[[UIApplication sharedApplication] openURL:[NSURL URLWithString:email]] ;
	
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
    [super viewDidLoad];
	
	self.title = @"Walk-in Centre" ;
	
	//STEP1. Load the information from the Array of the memory:
	
	
	NSString * filePath = [self dataFilePathWalkin] ;
	NSMutableArray * array = [[NSMutableArray alloc] initWithContentsOfFile:filePath] ;
	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSLog(@"Fichero cargado y correcto. Preparado para guardar el nuevo nombre.") ;
	}	
	
	//STEP2. Show the information in the labels:
	
	labelNombre.text = [array objectAtIndex:0 ] ;
	
	NSString * tempoString1 = [NSString alloc]  ;
	NSString * tempoString2 = [NSString alloc]  ;
	NSString * tempoString2b = [NSString alloc]  ;	
	NSString * tempoString3 = [NSString alloc]  ;
	NSString * tempoString4 = [NSString alloc]  ;
	
	tempoString1 = [array objectAtIndex:4 ] ;
	tempoString2 = [array objectAtIndex:5 ] ;
	tempoString2b = [array objectAtIndex:6 ] ;
	tempoString3 = [array objectAtIndex:7 ] ;	
	tempoString4 = [array objectAtIndex:8 ] ;	
	
	labelAddress1.text = [NSString stringWithFormat:@"%@", tempoString1] ;
	labelAddress1b.text = [NSString stringWithFormat:@"%@", tempoString2] ;
	labelAddress2b.text = [NSString stringWithFormat:@"%@", tempoString2b] ;
	labelAddress2.text = [NSString stringWithFormat:@"%@, %@", tempoString4, tempoString3] ;
	
	//informacion = [[NSString alloc] init] ;
	//informacion	= [NSString stringWithFormat:@"Name of the Emergency: %@. Ubication: %@, %@",labelNombre.text, labelAddress1.text, labelAddress2.text ] ;
	
	//NSLog(@"%@", informacion) ;
	
	labelPhone.text = [array objectAtIndex:9] ; 	
	[labelPhone release] ;
	
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
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
	
	if (avanzar == TRUE) {
		self.title = @"Back" ;
	}
	
}


-(void)viewWillAppear:(BOOL)animated{
	avanzar = TRUE ;		
}





@end

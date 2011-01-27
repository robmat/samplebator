


#import "hospitalMAPDetail.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import <AVFoundation/AVFoundation.h>


@implementation hospitalMAPDetail

-(NSString *) dataFilePathHospital{
	
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString * documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:kFilenameHospital ] ;
	
}


-(IBAction)sendMail:(id)sender{
	
	NSString * email = [[NSString alloc] init ] ;
	
	email = @"mailto:" ;
	
	informacion = [[NSString alloc] init] ;
	informacion	= [NSString stringWithFormat:@"Name of the Hospital: %@. Address: %@ %@, %@. Telephone number: %@",labelNombre.text, labelAddress1.text, labelAddress1b.text, labelAddress2.text, labelPhone.text ] ;
	
	informacion = [informacion stringByReplacingOccurrencesOfString:@"(null)" withString:@""] ;
	
	email = [NSString stringWithFormat: @"%@?Subject=NHS Yorkshire and Humber: Hospital information&body=%@", email, (@"%@", informacion)] ; 
	
	email = [email stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
	[[UIApplication sharedApplication] openURL:[NSURL URLWithString:email]] ;
	
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
    [super viewDidLoad];
	
	self.title = @"Hospital" ;
	
	//STEP1. Load the information from the Array of the memory:
	
	
	NSString * filePath = [self dataFilePathHospital ] ;
	NSMutableArray * array = [[NSMutableArray alloc] initWithContentsOfFile:filePath] ;
	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSLog(@"Fichero cargado y correcto. Preparado para guardar el nuevo nombre.") ;
	}	
	
	//STEP2. Show the information in the labels:
	
	labelNombre.text = [array objectAtIndex:0 ] ;
	
	NSString * tempoString1 = [array objectAtIndex:4];
	NSString * tempoString2 = [array objectAtIndex:5];
	NSString * tempoString3 = [array objectAtIndex:6];
	NSString * tempoString4 = [array objectAtIndex:7];
	
	//if (!tempoString1 || [tempoString1 isEqual:@""]) {
//		tempoString1 = tempoString2;
//		tempoString2 = @"";
//	}
//	
//	if (!tempoString2 || [tempoString2 isEqual:@""]) {
//		tempoString2 = [NSString stringWithFormat:@"%@ %@", tempoString3, tempoString4];
//		tempoString3 = @"";
//		tempoString4 = @"";
//	}
	
	labelAddress1.text = [NSString stringWithFormat:@"%@, %@, %@, %@", tempoString1, tempoString2, tempoString4, tempoString3] ;
	//labelAddress1b.text = [NSString stringWithFormat:@"%@", tempoString2] ;
	//labelAddress2.text = [NSString stringWithFormat:@"%@ %@", tempoString4, tempoString3] ;
	
	informacion = [[NSString alloc] init] ;
	informacion	= [NSString stringWithFormat:@"Name of the Hospital: %@. Address: %@ %@, %@",labelNombre.text, labelAddress1.text, labelAddress1b.text, labelAddress2.text ] ;
	
	NSLog(@"%@", informacion) ;
	
	labelPhone.text = [array objectAtIndex:8] ; 	
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
	
}



@end

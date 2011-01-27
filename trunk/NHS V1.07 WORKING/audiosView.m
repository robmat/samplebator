//
//  audiosView.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 30/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "audiosView.h"
#import <AVFoundation/AVFoundation.h>

@implementation audiosView

@synthesize scrollView ;

-(IBAction)buton1Action{
	if (boton1Pressed) {
		NSLog(@"Stop file") ;	
		[theAudio1 stop];
		[theAudio1 setCurrentTime:0] ;
		boton1Pressed = FALSE ;
		[boton1 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;	
	}else{
		[theAudio1 stop] ;
		[theAudio2 stop] ;
		[theAudio3 stop] ;
		[theAudio4 stop] ;
		[theAudio5 stop] ;
		[theAudio6 stop] ;
		[theAudio7 stop] ;
		[boton1 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton2 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton3 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton4 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton5 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton6 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton7 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[theAudio1 setCurrentTime:0] ;	
		[theAudio2 setCurrentTime:0] ;	
		[theAudio3 setCurrentTime:0] ;	
		[theAudio4 setCurrentTime:0] ;	
		[theAudio5 setCurrentTime:0] ;	
		[theAudio6 setCurrentTime:0] ;	
		[theAudio7 setCurrentTime:0] ;	
		//boton1Pressed = TRUE ;
		boton2Pressed = FALSE ;
		boton3Pressed = FALSE ;	
		boton4Pressed = FALSE ;
		boton5Pressed = FALSE ;
		boton6Pressed = FALSE ;	
		boton7Pressed = FALSE ;	
		
		
		NSLog(@"Play file") ;
		boton1Pressed = TRUE ;	
		[theAudio1 play];
		[boton1 setImage:[UIImage imageNamed:@"stop.png"] forState:UIControlStateNormal ] ;
		
	}
}


-(IBAction)buton2Action{

	if (boton2Pressed) {
		NSLog(@"Stop file") ;	
		boton2Pressed = FALSE ;
		[theAudio2 stop];
		[theAudio2 setCurrentTime:0] ;
		[boton2 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;	
	}else{
		[theAudio1 stop] ;
		[theAudio2 stop] ;
		[theAudio3 stop] ;
		[theAudio4 stop] ;
		[theAudio5 stop] ;
		[theAudio6 stop] ;
		[theAudio7 stop] ;
		[boton1 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton2 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton3 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton4 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton5 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton6 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton7 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[theAudio1 setCurrentTime:0] ;	
		[theAudio2 setCurrentTime:0] ;	
		[theAudio3 setCurrentTime:0] ;	
		[theAudio4 setCurrentTime:0] ;	
		[theAudio5 setCurrentTime:0] ;	
		[theAudio6 setCurrentTime:0] ;	
		[theAudio7 setCurrentTime:0] ;	
		boton1Pressed = FALSE ;
		//boton2Pressed = TRUE ;
		boton3Pressed = FALSE ;	
		boton4Pressed = FALSE ;
		boton5Pressed = FALSE ;
		boton6Pressed = FALSE ;	
		boton7Pressed = FALSE ;	
		
		NSLog(@"Play file") ;
		boton2Pressed = TRUE ;	
		[theAudio2 play];
		[boton2 setImage:[UIImage imageNamed:@"stop.png"] forState:UIControlStateNormal ] ;
		
	}	
}


-(IBAction)buton3Action{

	if (boton3Pressed) {
		NSLog(@"Stop file") ;	
		boton3Pressed = FALSE ;
		[theAudio3 stop];
		[theAudio3 setCurrentTime:0] ;
		[boton3 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;	
	}else{
		[theAudio1 stop] ;
		[theAudio2 stop] ;
		[theAudio3 stop] ;
		[theAudio4 stop] ;
		[theAudio5 stop] ;
		[theAudio6 stop] ;
		[theAudio7 stop] ;
		[boton1 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton2 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton3 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton4 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton5 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton6 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton7 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[theAudio1 setCurrentTime:0] ;	
		[theAudio2 setCurrentTime:0] ;	
		[theAudio3 setCurrentTime:0] ;	
		[theAudio4 setCurrentTime:0] ;	
		[theAudio5 setCurrentTime:0] ;	
		[theAudio6 setCurrentTime:0] ;	
		[theAudio7 setCurrentTime:0] ;	
		boton1Pressed = FALSE ;
		boton2Pressed = FALSE ;
		//boton3Pressed = FALSE ;	
		boton4Pressed = FALSE ;
		boton5Pressed = FALSE ;
		boton6Pressed = FALSE ;	
		boton7Pressed = FALSE ;			
		NSLog(@"Play file") ;
		boton3Pressed = TRUE ;
		[theAudio3 play];
		[boton3 setImage:[UIImage imageNamed:@"stop.png"] forState:UIControlStateNormal ] ;
	}
	
}


-(IBAction)buton4Action{

	if (boton4Pressed) {
		NSLog(@"Stop file") ;	
		boton4Pressed = FALSE ;
		[theAudio4 stop];
		[theAudio4 setCurrentTime:0] ;		
		[boton4 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;	
	}else{
		[theAudio1 stop] ;
		[theAudio2 stop] ;
		[theAudio3 stop] ;
		[theAudio4 stop] ;
		[theAudio5 stop] ;
		[theAudio6 stop] ;
		[theAudio7 stop] ;
		[boton1 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton2 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton3 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton4 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton5 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton6 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton7 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[theAudio1 setCurrentTime:0] ;	
		[theAudio2 setCurrentTime:0] ;	
		[theAudio3 setCurrentTime:0] ;	
		[theAudio4 setCurrentTime:0] ;	
		[theAudio5 setCurrentTime:0] ;	
		[theAudio6 setCurrentTime:0] ;	
		[theAudio7 setCurrentTime:0] ;
		boton1Pressed = FALSE ;
		boton2Pressed = FALSE ;
		boton3Pressed = FALSE ;	
		//boton4Pressed = FALSE ;
		boton5Pressed = FALSE ;
		boton6Pressed = FALSE ;	
		boton7Pressed = FALSE ;				
		NSLog(@"Play file") ;
		boton4Pressed = TRUE ;
		[theAudio4 play];	
		[boton4 setImage:[UIImage imageNamed:@"stop.png"] forState:UIControlStateNormal ] ;
	}
	
}


-(IBAction)buton5Action{

	if (boton5Pressed) {
		NSLog(@"Stop file") ;	
		boton5Pressed = FALSE ;
		[theAudio5 stop];
		[theAudio5 setCurrentTime:0] ;	
		[boton5 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;	
	}else{
		[theAudio1 stop] ;
		[theAudio2 stop] ;
		[theAudio3 stop] ;
		[theAudio4 stop] ;
		[theAudio5 stop] ;
		[theAudio6 stop] ;
		[theAudio7 stop] ;
		[boton1 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton2 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton3 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton4 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton5 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton6 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton7 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[theAudio1 setCurrentTime:0] ;	
		[theAudio2 setCurrentTime:0] ;	
		[theAudio3 setCurrentTime:0] ;	
		[theAudio4 setCurrentTime:0] ;	
		[theAudio5 setCurrentTime:0] ;	
		[theAudio6 setCurrentTime:0] ;	
		[theAudio7 setCurrentTime:0] ;	
		boton1Pressed = FALSE ;
		boton2Pressed = FALSE ;
		boton3Pressed = FALSE ;	
		boton4Pressed = FALSE ;
		//boton5Pressed = FALSE ;
		boton6Pressed = FALSE ;	
		boton7Pressed = FALSE ;				
		NSLog(@"Play file") ;
		[theAudio5 play];
		boton5Pressed = TRUE ;	
		[boton5 setImage:[UIImage imageNamed:@"stop.png"] forState:UIControlStateNormal ] ;
	}
	
}


-(IBAction)buton6Action{

	if (boton6Pressed) {
		NSLog(@"Stop file") ;	
		boton6Pressed = FALSE ;
		[theAudio6 stop];
		[theAudio6 setCurrentTime:0] ;			
		[boton6 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;	
	}else{
		[theAudio1 stop] ;
		[theAudio2 stop] ;
		[theAudio3 stop] ;
		[theAudio4 stop] ;
		[theAudio5 stop] ;
		[theAudio6 stop] ;
		[theAudio7 stop] ;
		[boton1 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton2 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton3 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton4 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton5 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton6 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton7 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[theAudio1 setCurrentTime:0] ;	
		[theAudio2 setCurrentTime:0] ;	
		[theAudio3 setCurrentTime:0] ;	
		[theAudio4 setCurrentTime:0] ;	
		[theAudio5 setCurrentTime:0] ;	
		[theAudio6 setCurrentTime:0] ;	
		[theAudio7 setCurrentTime:0] ;			
		boton1Pressed = FALSE ;
		boton2Pressed = FALSE ;
		boton3Pressed = FALSE ;	
		boton4Pressed = FALSE ;
		boton5Pressed = FALSE ;
		//boton6Pressed = FALSE ;	
		boton7Pressed = FALSE ;				
		NSLog(@"Play file") ;
		boton6Pressed = TRUE ;
		[theAudio6 play];	
		[boton6 setImage:[UIImage imageNamed:@"stop.png"] forState:UIControlStateNormal ] ;
	}
	
}

-(IBAction)buton7Action{

	if (boton7Pressed) {
		NSLog(@"Stop file") ;	
		boton7Pressed = FALSE ;
		[theAudio7 stop];
		[theAudio7 setCurrentTime:0] ;				
		[boton7 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;	
	}else{
		[theAudio1 stop] ;
		[theAudio2 stop] ;
		[theAudio3 stop] ;
		[theAudio4 stop] ;
		[theAudio5 stop] ;
		[theAudio6 stop] ;
		[theAudio7 stop] ;
		[boton1 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton2 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton3 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton4 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton5 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton6 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[boton7 setImage:[UIImage imageNamed:@"listen.png"] forState:UIControlStateNormal ] ;
		[theAudio1 setCurrentTime:0] ;	
		[theAudio2 setCurrentTime:0] ;	
		[theAudio3 setCurrentTime:0] ;	
		[theAudio4 setCurrentTime:0] ;	
		[theAudio5 setCurrentTime:0] ;	
		[theAudio6 setCurrentTime:0] ;	
		[theAudio7 setCurrentTime:0] ;	
		boton1Pressed = FALSE ;
		boton2Pressed = FALSE ;
		boton3Pressed = FALSE ;	
		boton4Pressed = FALSE ;
		boton5Pressed = FALSE ;
		boton6Pressed = FALSE ;	
		//boton7Pressed = FALSE ;				
		NSLog(@"Play file") ;
		boton7Pressed = TRUE ;	
		[theAudio7 play];
		[boton7 setImage:[UIImage imageNamed:@"stop.png"] forState:UIControlStateNormal ] ;
	}
	
}

;	






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
	
	self.title = @"First Aid Tips" ;
	
	boton1Pressed = FALSE ;
	boton2Pressed = FALSE ;
	boton3Pressed = FALSE ;
	boton4Pressed = FALSE ;
	boton5Pressed = FALSE ;
	boton6Pressed = FALSE ;
	boton7Pressed = FALSE ;
	
	path1 = [[NSBundle mainBundle] pathForResource:@"burns" ofType:@"mp3"];
	theAudio1 = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path1] error:NULL];

	path2 = [[NSBundle mainBundle] pathForResource:@"fits" ofType:@"mp3"];
	theAudio2 = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path2] error:NULL];
	
	path3 = [[NSBundle mainBundle] pathForResource:@"wounds" ofType:@"mp3"];
	theAudio3 = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path3] error:NULL];
	
	path4 = [[NSBundle mainBundle] pathForResource:@"breathing_but_unconscious" ofType:@"mp3"];
	theAudio4 = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path4] error:NULL];
	
	path5 = [[NSBundle mainBundle] pathForResource:@"cpr" ofType:@"mp3"];
	theAudio5 = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path5] error:NULL];
	
	path6 = [[NSBundle mainBundle] pathForResource:@"cpr_baby" ofType:@"mp3"];
	theAudio6 = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path6] error:NULL];
	
	path7 = [[NSBundle mainBundle] pathForResource:@"collapsed" ofType:@"mp3"];
	theAudio7 = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path7] error:NULL];
	

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

	[theAudio1 stop] ;
	[theAudio2 stop] ;
	[theAudio3 stop] ;
	[theAudio4 stop] ;
	[theAudio5 stop] ;
	[theAudio6 stop] ;
	[theAudio7 stop] ;
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
}

- (void)viewDidUnload {
	// Release any retained subviews of the main view.
	// e.g. self.myOutlet = nil;

}


- (void)dealloc {
    [super dealloc];
	
	//[path1 release] ;
	//[theAudio1 release] ;

	/*[path2 release] ;
	[theAudio2 release] ;
	
	[path3 release] ;
	[theAudio3 release] ;
	
	[path4 release] ;
	[theAudio4 release] ;
	
	[path5 release] ;
	[theAudio5 release] ;	
	*/
}

-(IBAction)MP3online{


}


@end

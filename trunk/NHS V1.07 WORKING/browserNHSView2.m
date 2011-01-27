//
//  browserNHSView2.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 19/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "browserNHSView2.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import <AVFoundation/AVAudioPlayer.h>

@implementation browserNHSView2

@synthesize activity, TheWebView;
@synthesize urlString;
@synthesize title;
@synthesize homeButton;


-(IBAction)GoHome:(id)sender
{
	NSURL *url = [NSURL URLWithString:urlString];
	NSURLRequest *request = [NSURLRequest requestWithURL:url];
	[TheWebView loadRequest:request];
	
	[[self TheWebView] setDelegate:self] ;
	
    [super viewDidLoad];
}

-(IBAction)GoBack:(id)sender{
	[TheWebView goBack] ;
}

-(IBAction)GoForward:(id)sender{
	[TheWebView goForward] ;
}



-(void)webViewDidStartLoad:(UIWebView *)TheWebView{
	[activity startAnimating] ;
}

-(void)webViewDidFinishLoad:(UIWebView *)TheWebView{
	[activity stopAnimating] ;
}

-(void)webView:(UIWebView*)webView didFailLoadWithError:(NSError*)error{
	NSLog(@"Failed to load web content. Reason: %@",error);
}


// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.

- (void)viewDidLoad {
	estado_anterior = 0 ;
	estado_siguiente = 0 ;
	
	imagenTermo.hidden = TRUE ; 
	
	self.title = title ;
	activity.hidesWhenStopped = TRUE ;
	
	NSURL *url = [NSURL URLWithString:urlString];
	NSURLRequest *request = [NSURLRequest requestWithURL:url];
	[TheWebView loadRequest:request];
	
	[[self TheWebView] setDelegate:self] ;
	
	homeButton.title = title;
	
    [super viewDidLoad];
	
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
	[activity release] ;
	[TheWebView release] ;
    [super dealloc];
}

-(void)viewWillDisappear:(BOOL)animated{
	
	imagenTermo.hidden = TRUE ;

	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];

}

@end

//
//  browserNHSView.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 19/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "browserNHSView.h"


@implementation browserNHSView

@synthesize activity, TheWebView;


-(IBAction)GoBack:(id)sender{
	[TheWebView goBack] ;
}

-(IBAction)GoForward:(id)sender{
	[TheWebView goForward] ;
}

-(IBAction)GoHome:(id)sender{
	NSURL *url = [NSURL URLWithString:@"http://www.apple.com"];
	NSURLRequest *request = [NSURLRequest requestWithURL:url];
	[TheWebView loadRequest:request];

}

-(IBAction)Refresh:(id)sender{
	[TheWebView reload] ;
}

-(void)webViewDidStartLoad:(UIWebView *)TheWebView{
	[activity startAnimating] ;
}

-(void)webViewDidFinishLoad:(UIWebView *)TheWebView{
	[activity stopAnimating] ;
}



- (void)viewDidLoad {

	NSURL *url = [NSURL URLWithString:@"http://www.apple.com"];
	NSURLRequest *request = [NSURLRequest requestWithURL:url];
	[TheWebView loadRequest:request];
	
	[[self TheWebView] setDelegate:self] ;
	
    [super viewDidLoad];
	
}


- (void)dealloc {
    //[window release];
    [super dealloc];
}

/*
- (void)didReceiveMemoryWarning {
	// Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
	
	// Release any cached data, images, etc that aren't in use.
}*/

- (void)viewDidUnload {
	// Release any retained subviews of the main view.
	// e.g. self.myOutlet = nil;
}




@end

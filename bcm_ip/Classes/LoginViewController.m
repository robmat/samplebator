//
//  LoginViewController.m
//  bcm_ip
//
//  Created by User on 3/5/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "LoginViewController.h"
#import "ASIHTTPRequest.h"
#import "bcm_ipAppDelegate.h"
#import "ASIFormDataRequest.h"

@implementation LoginViewController

@synthesize loginBtn, userLbl, passLbl, siteLbl, userTxtFld, passTxtFld, siteTxtFld;

/*
 // The designated initializer.  Override if you create the controller programmatically and want to perform customization that is not appropriate for viewDidLoad.
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    if ((self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil])) {
        // Custom initialization
    }
    return self;
}
*/
- (NSString*) getLoginDataFilePath {
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	return [[paths objectAtIndex:0] stringByAppendingPathComponent:@"loginData.plist"];
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	NSString* filePath = [self getLoginDataFilePath];
	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSArray* loginDataArr = [NSArray arrayWithContentsOfFile: filePath];
		if ([loginDataArr count] == 3) {
			userTxtFld.text = [loginDataArr objectAtIndex:0];
			passTxtFld.text = [loginDataArr objectAtIndex:1];
			siteTxtFld.text = [loginDataArr objectAtIndex:2];
		}
	}	
	
	[super viewDidLoad];
}
- (IBAction) loginAction: (id) sender {
	NSString* urlStr = [NSString stringWithString: [bcm_ipAppDelegate baseURL]];
	urlStr = [urlStr stringByAppendingString:siteTxtFld.text];
	urlStr = [urlStr stringByAppendingString: [bcm_ipAppDelegate apiSuffix]];
	
	NSURL *url = [NSURL URLWithString:urlStr];
	ASIFormDataRequest *request = [ASIFormDataRequest requestWithURL:url];
	[request setPostValue: @"validateUser" forKey:@"action"];
	[request setPostValue: userTxtFld.text forKey:@"user"];
	[request setPostValue: passTxtFld.text forKey:@"password"];
	[request setPostValue: [UIDevice currentDevice].uniqueIdentifier forKey:@"id"];
	[request setDelegate:self];
	[request startAsynchronous];
}

- (void)requestFinished:(ASIHTTPRequest *)request
{
	NSString *responseString = [request responseString];
	NSLog(@"Response: %@", responseString);
	if ( [responseString isEqual:@"<response>ok</response>"] ) {
		NSArray* loginDataArr = [NSArray arrayWithObjects: userTxtFld.text, passTxtFld.text, siteTxtFld.text, nil ];
		[loginDataArr writeToFile:[self getLoginDataFilePath] atomically: YES];
	} else {
		
	}	
}

- (void)requestFailed:(ASIHTTPRequest *)request
{
	//NSError *error = [request error];
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
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
}


- (void)dealloc {
	[loginBtn release];
    [super dealloc];
}


@end

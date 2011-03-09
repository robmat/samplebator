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
#import "Dictionary.h"
#import "MainMenuViewController.h"

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
// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	NSString* filePath = [bcm_ipAppDelegate getLoginDataFilePath];
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSArray* loginDataArr = [NSArray arrayWithContentsOfFile: filePath];
		if ([loginDataArr count] == 3) {
			userTxtFld.text = [loginDataArr objectAtIndex:0];
			passTxtFld.text = [loginDataArr objectAtIndex:1];
			siteTxtFld.text = [loginDataArr objectAtIndex:2];
		}
	}	
	userTxtFld.delegate = self;
	passTxtFld.delegate = self;
	siteTxtFld.delegate = self;
	[super viewDidLoad];
}

- (BOOL)textFieldShouldReturn:(UITextField *)textField {
	if (textField == userTxtFld) {
		[passTxtFld becomeFirstResponder];
	} else if (textField == passTxtFld) {
		[siteTxtFld becomeFirstResponder];
	} else if (textField == siteTxtFld) {
		[siteTxtFld resignFirstResponder];
	}
	return YES;
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
	[request setPostValue: [UIDevice currentDevice].uniqueIdentifier forKey:@"devid"];
	[request setPostValue: [Dictionary localeAbbr] forKey:@"lang"];
	[request setDelegate:self];
	[request startAsynchronous];
}

- (void)requestFinished:(ASIHTTPRequest *)request {
	NSString *responseString = [request responseString];
	if ( [responseString isEqual:@"<response>ok</response>"] ) {
		NSArray* loginDataArr = [NSArray arrayWithObjects: userTxtFld.text, passTxtFld.text, siteTxtFld.text, nil ];
		[loginDataArr writeToFile:[bcm_ipAppDelegate getLoginDataFilePath] atomically: YES];
		Dictionary* dict = [[[Dictionary alloc] init] autorelease];	
		[dict loadDictionaryAndRetry:YES asynchronous:NO overwrite:YES];
		MainMenuViewController* mmvc = [[MainMenuViewController alloc] initWithNibName:@"MainMenuViewController" bundle:nil];
		[self.navigationController pushViewController:mmvc animated:YES];
		[mmvc release];
	} else {
		UIAlertView* alert;
		if ( [responseString rangeOfString:@"HTTP 404"].location != NSNotFound ) {
			alert = [[UIAlertView alloc] initWithTitle: NSLocalizedString(@"errorDialogTitle", nil) message: NSLocalizedString(@"errorDialog404Message", nil) delegate: nil cancelButtonTitle: @"OK" otherButtonTitles: nil];
		} else if ( [responseString rangeOfString:@"<response isError='true'>Wrong authorization</response>"].location != NSNotFound ) {
			alert = [[UIAlertView alloc] initWithTitle: NSLocalizedString(@"errorDialogTitle", nil) message: NSLocalizedString(@"errorDialogWrongCredentialsMessage", nil) delegate: nil cancelButtonTitle: @"OK" otherButtonTitles: nil];
		}
		[alert show];
		[alert release];
	}	
}

- (void)requestFailed:(ASIHTTPRequest *)request {
	NSError *error = [request error];
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle: NSLocalizedString(@"errorDialogTitle", nil) message: [error localizedDescription] delegate: nil cancelButtonTitle: @"OK" otherButtonTitles: nil];
	[alert show];
	[alert release];
}
- (void)textFieldDidBeginEditing:(UITextField *)textField
{
    [self animateTextField: textField up: YES];
}
- (void)textFieldDidEndEditing:(UITextField *)textField
{
    [self animateTextField: textField up: NO];
}

- (void) animateTextField: (UITextField*) textField up: (BOOL) up
{
    const int movementDistance = 80; // tweak as needed
    const float movementDuration = 0.3f; // tweak as needed
	
    int movement = (up ? -movementDistance : movementDistance);
	
    [UIView beginAnimations: @"anim" context: nil];
    [UIView setAnimationBeginsFromCurrentState: YES];
    [UIView setAnimationDuration: movementDuration];
    self.view.frame = CGRectOffset(self.view.frame, 0, movement);
    [UIView commitAnimations];
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

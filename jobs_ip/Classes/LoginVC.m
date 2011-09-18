#import "LoginVC.h"
#import "ASIHTTPRequest.h"
#import "CXMLDocument.h"
#import "CXMLElement.h"

@implementation LoginVC

@synthesize loginTxt, passwTxt, viewController;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}

- (void)loginAction: (id) sender {
	NSString* loginUrlStr = [NSString stringWithFormat:@"http://jobstelecom.com/development/wsapi/mobile/login?username=%@&password=%@", loginTxt.text, passwTxt.text];
	NSURL* url = [NSURL URLWithString:loginUrlStr];
	ASIHTTPRequest* req = [ASIHTTPRequest requestWithURL:url];
	[req setDelegate:self];
	[req startAsynchronous];
}

- (void)requestFinished:(ASIHTTPRequest *)request {
	CXMLDocument* doc = [[CXMLDocument alloc] initWithData:[request responseData] options:0 error:nil];
	CXMLElement* loggedIn = [[doc nodesForXPath:@"/LoginAPI/LoggedIn" error:nil] objectAtIndex:0];
	if ([@"true" isEqualToString:[loggedIn stringValue]]) {
		UINavigationController* contr = self.navigationController;
		[contr popViewControllerAnimated:NO];
		[contr pushViewController:viewController animated:YES];
	} else {
		NSString* msg = [[request responseString] rangeOfString:@"INVALID_PASSWORD"].location != NSNotFound ? @"Invalid pasword" : @"";
		msg = [[request responseString] rangeOfString:@"NO_SUCH_USER"].location != NSNotFound ? @"Wrong user name" : @"";
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Login failed" message:msg delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
		[alert show];
		[alert release];
	}
}

- (void)requestFailed:(ASIHTTPRequest *)request {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[[request error] localizedDescription] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
}

- (void)registerAction: (id) sender {

}

- (void)forgotAction: (id) sender {

}

- (void)viewDidLoad {
    [super viewDidLoad];
	[self hideBackBtn];
}

- (void)dealloc {
    [super dealloc];
	[passwTxt release];
	[loginTxt release];
	[viewController release];
}

@end

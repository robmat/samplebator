#import "LoginVC.h"
#import "ASIHTTPRequest.h"
#import "CXMLDocument.h"
#import "CXMLElement.h"
#import "ASIFormDataRequest.h"

@implementation LoginVC

@synthesize loginTxt, passwTxt, viewController;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}

- (void)loginAction: (id) sender {
	NSString* loginUrlStr = [NSString stringWithFormat:@"http://jobstelecom.com/development/wsapi/mobile/login"];
	NSURL* url = [NSURL URLWithString:loginUrlStr];
	ASIFormDataRequest* req = [ASIFormDataRequest requestWithURL:url];
	[req setRequestMethod:@"POST"];
	[req addPostValue:loginTxt.text forKey:@"username"];
	[req addPostValue:passwTxt.text forKey:@"password"];
	[req setDelegate:self];
	[req startAsynchronous];
}
- (BOOL)textFieldShouldReturn:(UITextField *)textField {
	[self loginAction:textField];
	return NO;
}
- (void)requestFinished:(ASIHTTPRequest *)request {
	CXMLDocument* doc = [[CXMLDocument alloc] initWithData:[request responseData] options:0 error:nil];
	CXMLElement* loggedIn = [[doc nodesForXPath:@"/LoginAPI/LoggedIn" error:nil] objectAtIndex:0];
	if ([@"true" isEqualToString:[loggedIn stringValue]]) {
		UINavigationController* contr = self.navigationController;
		[contr popViewControllerAnimated:NO];
		if (viewController != nil) {
			[contr pushViewController:viewController animated:YES];
		}
	} else {
		NSString* msg = [[request responseString] rangeOfString:@"INVALID_PASSWORD"].location != NSNotFound ? @"Invalid pasword" : @"";
		msg = [[request responseString] rangeOfString:@"NO_SUCH_USER"].location != NSNotFound ? @"Wrong user name" : @"";
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Login failed" message:msg delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
		[alert show];
		[alert release];
	}
}
- (void)viewWillAppear:(BOOL)animated {
	[super viewWillAppear:animated];
	self.navigationController.navigationBarHidden = NO;
	self.navigationItem.rightBarButtonItem = [[UIBarButtonItem alloc] initWithTitle:@"Register" style:UIBarStyleDefault target:self action:@selector(registerAction:)];
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
	self.title = @"";
}
- (void)dealloc {
    [super dealloc];
	[passwTxt release];
	[loginTxt release];
	[viewController release];
}

@end

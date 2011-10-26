#import "ApplyVC.h"
#import "ASIHTTPRequest.h"
#import "ApplySuccessVC.h"
#import "ASIFormDataRequest.h"

@implementation ApplyVC

@synthesize textView, jobId, jobTitle, cvId, titlLbl, descLbl, dateLbl;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}
- (void)applyAction: (id) sender {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Confirm" message:@"Are you sure you want to apply for this job?" delegate:self 
										  cancelButtonTitle:@"Cancel" otherButtonTitles:@"YES", nil];
	[alert show];
	[alert release];
}
- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex {
	if (buttonIndex == 0) {
		[alertView dismissWithClickedButtonIndex:0 animated:YES];
		return;
	}
	ASIFormDataRequest* request = [ASIFormDataRequest requestWithURL:[NSURL URLWithString:@"http://jobstelecom.com/development/wsapi/mobile/applynow"]];
	[request setRequestMethod:@"POST"];
	[request addPostValue:jobId forKey:@"JobSID"];
	[request addPostValue:cvId forKey:@"ResumeID"];
	[request addPostValue:[textView.text stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding] forKey:@"CoverNote"];
	[request setDelegate:self];
	[request startAsynchronous];
}
- (void)requestFinished:(ASIHTTPRequest *)request {
	NSString* responseString = [request responseString];
	if ([responseString rangeOfString:@"ApplicationSent"].location != NSNotFound) {
		ApplySuccessVC* asvc = [[ApplySuccessVC alloc] init];
		[self.navigationController pushViewController:asvc animated:YES];
		[asvc release];
	} else if ([[request responseString] rangeOfString:@"UserAlreadyApplied"].location != NSNotFound) {
		NSLog(@"%@", @"Already applied");
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Warning" message:@"You've already applied for this job" delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
		[alert show];
		[alert release];
	}
}
- (void)requestFailed:(ASIHTTPRequest *)request {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[[request error] localizedDescription] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
}
- (void)viewWillAppear:(BOOL)animated {
	[super viewWillAppear:animated];
	self.navigationController.navigationBarHidden = NO;
	self.navigationItem.rightBarButtonItem = [[UIBarButtonItem alloc] initWithTitle:@"send application" style:UIBarStyleDefault target:self action:@selector(applyAction:)];
}
- (void)viewDidLoad {
    [super viewDidLoad];
	backBtn.hidden = YES;
	textView.placeholder = @"cover letter (optional)";
	self.title = @"apply";
}
- (void)dealloc {
    [super dealloc];
	[textView release];
	[cvId release];
	[jobId release];
	[jobTitle release];
	[titlLbl release];
	[descLbl release];
	[dateLbl release];
}

@end

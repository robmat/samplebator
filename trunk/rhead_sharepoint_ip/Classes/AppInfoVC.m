#import "AppInfoVC.h"
#import "WebViewVC.h"

@implementation AppInfoVC

@synthesize webView;

- (void)viewDidLoad {
    [super viewDidLoad];
	backBtn.hidden = YES;
	self.navigationItem.titleView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"title_image.png"]];
	[self setUpTabBarButtons];
    infoBtn.hidden = YES;
    NSString* path = [[NSBundle mainBundle] pathForResource:@"appinfo" ofType:@"html"];
    NSString* htmlStr = [NSString stringWithContentsOfFile:path encoding:NSUTF8StringEncoding error:nil];
    [webView loadHTMLString:htmlStr baseURL: [NSURL URLWithString: @"http://fake.url"]];
}
- (BOOL) webView:(UIWebView *)webView shouldStartLoadWithRequest:(NSURLRequest *)request navigationType:(UIWebViewNavigationType)navigationType {
    NSURL* url = [request URL];
    NSLog(@"%@", [url absoluteString]);
    if ([[url host] isEqualToString:@"fake.url"]) {
        return YES;
    }
    if ([[url scheme] isEqualToString:@"http"]) {
        [self visitAction: [url absoluteString]];
        return NO;
    }
    if ([[url scheme] isEqualToString:@"mailto"]) {
        [self mailtoAction: [url absoluteString]];
        return NO;
    }
    [[UIApplication sharedApplication] openURL:url];
    return NO;
}
- (IBAction)mailtoAction: (NSString*) url {
    url = [url stringByReplacingOccurrencesOfString:@"mailto:" withString:@""];
    MFMailComposeViewController* mailController = [self mailController];
    [mailController setToRecipients:[NSArray arrayWithObjects:url, nil]];
    [self.navigationController presentModalViewController:mailController animated:YES];
}
- (void)mailComposeController:(MFMailComposeViewController*)controller  
          didFinishWithResult:(MFMailComposeResult)result 
                        error:(NSError*)error {
	if (result == MFMailComposeResultCancelled) {
        [self.navigationController dismissModalViewControllerAnimated:YES];
		return;
	}
	if (result == MFMailComposeResultSent) {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Mail sent." 
														message:@"Sending the mail succeeded." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	} else {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Failure." 
														message:@"Sending the mail failed for unknown reason, try again later." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	}
}
- (MFMailComposeViewController*)mailController {
	MFMailComposeViewController* controller = [[MFMailComposeViewController alloc] init];
	if ([MFMailComposeViewController canSendMail] && controller != nil)	{
		controller.mailComposeDelegate = self;
		[controller setSubject:@""];
		[controller setMessageBody:@"" isHTML:NO]; 
        [controller setTitle:@"Contact"];
		[controller autorelease];
        return controller;
	} else {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Failure." 
														message:@"Can't send mail, probably no email account is set up." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	}
    return nil;
}
- (IBAction)visitAction: (NSString*) url {
	WebViewVC* wwvc = [[WebViewVC alloc] init];
	wwvc.url = url;
	wwvc->dontAppendPass = YES;
    wwvc.title = @"www.rheadgroup.com";
	[self.navigationController pushViewController:wwvc animated:YES];
	[wwvc release];
}
- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
    [[UIDevice currentDevice] setOrientation:UIInterfaceOrientationPortrait];
}
- (void)dealloc {
    [super dealloc];
}

@end

#import "WebViewVC.h"
#import "rhead_sharepoint_ipAppDelegate.h"

@implementation WebViewVC

@synthesize webView, url, indicator, bottomBar, blankBar;

- (void)viewDidLoad {
    [super viewDidLoad];
	NSDictionary* loginDict = [NSDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate loginDictPath]];
	NSString* loginPassStr = [NSString stringWithFormat:@"https://%@:%@@", [loginDict objectForKey:@"login"], [loginDict objectForKey:@"password"]];
	NSString* urlStr = [url stringByReplacingOccurrencesOfString:@"https://" withString:@""];
	if (!dontAppendPass) {
        loginPassStr = [loginPassStr stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
		urlStr = [NSString stringWithFormat:@"%@%@", loginPassStr, urlStr];
	}
	NSURLRequest* urlRequest = [NSURLRequest requestWithURL:[NSURL URLWithString:urlStr]];
	[webView loadRequest:urlRequest];
	NSLog(@"%@", urlStr);
	backBtn.hidden = YES;
	[self setUpTabBarButtons];
	webView.autoresizingMask = UIViewAutoresizingFlexibleHeight | UIViewAutoresizingFlexibleWidth;
    self.navigationController.navigationBarHidden = NO;
}
- (BOOL)webView:(UIWebView *)webView shouldStartLoadWithRequest:(NSURLRequest *)request navigationType:(UIWebViewNavigationType)navigationType {
	self.indicator.hidden = NO;
	[self.indicator startAnimating];
	return YES;
}
- (void)webView:(UIWebView *)webView didFailLoadWithError:(NSError *)error {
    UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[error localizedDescription] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
    [alert show];
    [alert release];
}
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)orientation {
    return YES;
}
- (void)willAnimateRotationToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation duration:(NSTimeInterval)duration {
    [self setUpViewByOrientation: toInterfaceOrientation];
}
- (void)setUpViewByOrientation: (UIInterfaceOrientation)toInterfaceOrientation {
    if (toInterfaceOrientation==UIInterfaceOrientationLandscapeLeft|| toInterfaceOrientation== UIInterfaceOrientationLandscapeRight) {        
        //self.webView.frame = CGRectMake(0, 0, 480, 226);
        self.indicator.frame = CGRectMake(230, 118, 20, 20);
    } else {
        //self.webView.frame = CGRectMake(0, 0, 327, 374);
        self.indicator.frame = CGRectMake(153, 177, 20, 20);
    }
}
- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
    [self setUpViewByOrientation:[UIDevice currentDevice].orientation];
}
- (void)webViewDidFinishLoad:(UIWebView *)webView {
	self.indicator.hidden = YES;
	[self.indicator stopAnimating];
}
- (void)dealloc {
    [super dealloc];
	[webView release];
	[url release];
	[indicator release];
    [bottomBar release];
    [blankBar release];
}

@end

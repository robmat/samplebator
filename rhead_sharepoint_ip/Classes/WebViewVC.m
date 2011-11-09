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
		urlStr = [NSString stringWithFormat:@"%@%@", loginPassStr, urlStr];
	}
	NSURLRequest* urlRequest = [NSURLRequest requestWithURL:[NSURL URLWithString:urlStr]];
	[webView loadRequest:urlRequest];
	NSLog(@"%@", urlStr);
	backBtn.hidden = YES;
	[self setUpTabBarButtons];
	webView.autoresizingMask = UIViewAutoresizingFlexibleHeight | UIViewAutoresizingFlexibleWidth;
}
- (BOOL)webView:(UIWebView *)webView shouldStartLoadWithRequest:(NSURLRequest *)request navigationType:(UIWebViewNavigationType)navigationType {
	self.indicator.hidden = NO;
	[self.indicator startAnimating];
	return YES;
}
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)orientation {
    return YES;
}
- (void)willAnimateRotationToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation duration:(NSTimeInterval)duration {
    [self setUpViewByOrientation: toInterfaceOrientation];
}
- (void)setUpViewByOrientation: (UIInterfaceOrientation)toInterfaceOrientation {
    if (toInterfaceOrientation==UIInterfaceOrientationPortrait || toInterfaceOrientation== UIInterfaceOrientationPortraitUpsideDown) {
        self.webView.frame = CGRectMake(0, 0, 327, 374);
        self.bottomBar.frame = CGRectMake(0, 372, 320, 46);
        infoBtn.frame = CGRectMake(30, 376, 45, 37);
        newsBtn.frame = CGRectMake(243, 376, 45, 37);
        contactBtn.frame = CGRectMake(98, 376, 45, 37);
        self.indicator.frame = CGRectMake(153, 177, 20, 20);
        self.blankBar.hidden = YES;
    } else {
        self.webView.frame = CGRectMake(0, 0, 480, 226);
        self.bottomBar.frame = CGRectMake(80, 224, 320, 46);
        infoBtn.frame = CGRectMake(108, 227, 45, 37);
        newsBtn.frame = CGRectMake(321, 227, 45, 37);
        contactBtn.frame = CGRectMake(176, 227, 45, 37);
        self.indicator.frame = CGRectMake(230, 118, 20, 20);
        self.blankBar.hidden = NO;
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

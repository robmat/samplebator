#import "CompetitorsVC.h"

@implementation CompetitorsVC

@synthesize webView;

- (void)viewDidLoad {
    [super viewDidLoad];
	self.title = @"Competition";
	NSString* path = [[NSBundle mainBundle] pathForResource:@"competitors" ofType:@"html"];
	NSString* html = [NSString stringWithContentsOfFile:path encoding:NSUTF8StringEncoding error:nil];
	[webView loadHTMLString:html baseURL:[NSURL URLWithString: @"http://foo.bar"]];
}
- (void)viewWillAppear:(BOOL)animated {
	self.navigationController.navigationBarHidden = NO;
	backBtn.hidden = YES;
}
- (void)dealloc {
    [super dealloc];
}

@end

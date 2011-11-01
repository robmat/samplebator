#import "AppInfoVC.h"
#import "WebViewVC.h"

@implementation AppInfoVC

- (void)viewDidLoad {
    [super viewDidLoad];
	backBtn.hidden = YES;
	self.title = @"Rhead Group App";
	[self setUpTabBarButtons];
}
- (IBAction)visitAction: (id) sender {
	WebViewVC* wwvc = [[WebViewVC alloc] init];
	wwvc.url = @"http://www.rheadgroup.com";
	wwvc->dontAppendPass = YES;
	[self.navigationController pushViewController:wwvc animated:YES];
	[wwvc release];
}
- (void)dealloc {
    [super dealloc];
}

@end

#import "ServicesVC.h"

@implementation ServicesVC

- (void)viewDidLoad {
    [super viewDidLoad];
	self.title = @"Services";
	backBtn.hidden = YES;
	[self setUpTabBarButtons];
}
- (void)dealloc {
    [super dealloc];
}

@end

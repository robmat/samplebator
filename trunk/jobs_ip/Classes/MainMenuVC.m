#import "MainMenuVC.h"
#import "SavedSearchVC.h"
#import "MyCVs.h"
#import "MyFavJobsVC.h"

@implementation MainMenuVC

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}
- (IBAction)mySearchesAction {
	SavedSearchVC* ssvc = [[SavedSearchVC alloc] init];
	[self.navigationController pushViewController:ssvc animated:YES];
	[ssvc release];
}
- (IBAction)myCvsAction {
	MyCVs* ssvc = [[MyCVs alloc] init];
	[self.navigationController pushViewController:ssvc animated:YES];
	[ssvc release];
}
- (IBAction)favJobsAction {
	MyFavJobsVC* mfjvc = [[MyFavJobsVC alloc] init];
	[self.navigationController pushViewController:mfjvc animated:YES];
	[mfjvc release];
}
- (IBAction)applicationsAction {
}
- (IBAction)myProfileAction {
}
- (IBAction)meRecruitersAction {
}
- (void)viewDidLoad {
    [super viewDidLoad];
	tabMainMenuBtn.hidden = YES;
	backBtn.hidden = YES;
}

- (void)dealloc {
    [super dealloc];
}

@end
